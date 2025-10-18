<?php

namespace App\Observers;

use App\Models\Compra;
use Illuminate\Support\Facades\DB;

class CompraObserver
{
    /**
     * Handle the Compra "created" event.
     */
    public function created(Compra $compra): void
    {
        $this->syncEstadoPago($compra);

        // Si la compra se crea ya como FACTURADO, aumentar stock/entradas
        if ($this->getEstadoActual($compra) === 'FACTURADO') {
            $this->increaseStockFromCompra($compra);
        }
    }

    // Almacén temporal de snapshots para evitar persistir datos en la BD
    private static array $snapshots = [];

    /**
     * Handle the Compra "updating" event.
     * Captura snapshot de estado y detalles antes de que se persistan los cambios.
     */
    public function updating(Compra $compra): void
    {
        $key = $compra->getKey();
        // guarda snapshot fuera del modelo (no se persiste)
        self::$snapshots[$key] = [
            'estado' => $this->getOriginalEstado($compra),
            'detalles' => $this->getDetalleRows($compra)
                ->map(function ($r) {
                    [$productoId, $cantidad] = $this->extractProductoAndCantidad($r);
                    return ['producto_id' => $productoId, 'cantidad' => (float) $cantidad];
                })->toArray(),
        ];
    }

    /**
     * Handle the Compra "updated" event.
     */
    public function updated(Compra $compra): void
    {
        $this->syncEstadoPago($compra);

        $key = $compra->getKey();
        $snapshot = self::$snapshots[$key] ?? null;

        $originalEstado = $this->getOriginalEstado($compra) ?? ($snapshot['estado'] ?? null);
        $currentEstado = $this->getEstadoActual($compra);

        // Si pasó a FACTURADO (antes no lo era) -> incrementar stock/entradas con cantidades actuales
        if ($originalEstado !== 'FACTURADO' && $currentEstado === 'FACTURADO') {
            $this->increaseStockFromCompra($compra);
        }

        // Si antes era FACTURADO y ahora ya no -> revertir (decrementar) stock/entradas usando snapshot original
        if ($originalEstado === 'FACTURADO' && $currentEstado !== 'FACTURADO') {
            $rowsSnapshot = $snapshot['detalles'] ?? null;
            $this->decreaseStockFromCompra($compra, $rowsSnapshot);
        }

        // limpiar snapshot usado
        if (isset(self::$snapshots[$key])) {
            unset(self::$snapshots[$key]);
        }

        // Si sigue siendo FACTURADO y cambian cantidades, habría que comparar snapshot vs actuales
    }

    public function deleted(Compra $compra): void
    {
        // Si se elimina una compra que estaba FACTURADA, revertir stock/entradas
        $originalEstado = $this->getOriginalEstado($compra);
        $currentEstado = $this->getEstadoActual($compra);

        // En borrado (soft o hard) si estaba FACTURADO revertimos
        if ($originalEstado === 'FACTURADO' || $currentEstado === 'FACTURADO') {
            $this->decreaseStockFromCompra($compra);
        }
    }

    public function restored(Compra $compra): void
    {
        // Si se restaura y quedó/está FACTURADO, aseguramos el incremento
        $currentEstado = $this->getEstadoActual($compra);
        if ($currentEstado === 'FACTURADO') {
            $this->increaseStockFromCompra($compra);
        }
    }

    public function forceDeleted(Compra $compra): void
    {
        // Si se fuerza borrado y era FACTURADO, revertir
        $originalEstado = $this->getOriginalEstado($compra);
        $currentEstado = $this->getEstadoActual($compra);
        if ($originalEstado === 'FACTURADO' || $currentEstado === 'FACTURADO') {
            $this->decreaseStockFromCompra($compra);
        }
    }

    /**
     * Sincroniza estado_pago según total a pagar (SALDADO si 0, EN_CARTERA si > 0)
     */
    private function syncEstadoPago(Compra $compra): void
    {
        $totalAPagar = $this->computeTotalAPagar($compra);

        $nuevoEstadoPago = (round($totalAPagar, 4) <= 0.0001) ? 'SALDADO' : 'EN_CARTERA';

        if (($compra->estado_pago ?? null) !== $nuevoEstadoPago) {
            // updateQuietly evita disparar observers recursivamente
            $compra->updateQuietly(['estado_pago' => $nuevoEstadoPago]);
        }
    }

    /**
     * Calcula un total a pagar fiable intentando usar campo total_a_pagar
     * o calculándolo desde subtotal - abonos - descuento.
     */
    private function computeTotalAPagar(Compra $compra): float
    {
        // Si ya existe el campo total_a_pagar en el modelo, usarlo
        if (isset($compra->total_a_pagar)) {
            return (float) $compra->total_a_pagar;
        }

        $subtotal = (float) ($compra->subtotal ?? 0);
        $descuento = (float) ($compra->descuento ?? 0);
        $abonosTotal = 0.0;

        // posibles nombres de relaciones de abonos y posibles nombres de columna de monto
        $relaciones = ['abonos', 'abonoCompra', 'abonosCompra', 'abonos_compra'];
        $montoCols = ['monto', 'monto_abono_compra', 'monto_abono', 'amount', 'valor'];

        foreach ($relaciones as $rel) {
            if (method_exists($compra, $rel)) {
                try {
                    $rows = $compra->{$rel}()->get();
                    foreach ($rows as $r) {
                        foreach ($montoCols as $col) {
                            if (isset($r->{$col})) {
                                $abonosTotal += (float) $r->{$col};
                                break;
                            }
                        }
                    }
                    // si encontramos filas, no probamos otras relaciones
                    if ($rows->isNotEmpty()) {
                        break;
                    }
                } catch (\Throwable $e) {
                    // ignorar y probar siguiente relación
                }
            }
        }

        $total = $subtotal - $abonosTotal - $descuento;
        return $total < 0 ? 0.0 : (float) $total;
    }

    /**
     * Incrementa el stock y entradas de productos según los detalles de la compra.
     * Usa update con DB::raw para actualizar ambas columnas de forma atómica.
     */
    private function increaseStockFromCompra(Compra $compra): void
    {
        $rows = $this->getDetalleRows($compra);
        foreach ($rows as $r) {
            [$productoId, $cantidad] = $this->extractProductoAndCantidad($r);
            if ($productoId !== null && $cantidad > 0) {
                $q = (int) round($cantidad);
                DB::table('productos')
                    ->where('id', $productoId)
                    ->update([
                        'stock' => DB::raw("stock + {$q}"),
                        'entradas' => DB::raw("entradas + {$q}"),
                    ]);
            }
        }
    }

    /**
     * Decrementa (revierte) el stock y entradas según los detalles de la compra.
     * Si se pasa $rowsSnapshot (array), lo usa; si no, lee detalles actuales.
     * Evita valores negativos usando GREATEST(..., 0).
     */
    private function decreaseStockFromCompra(Compra $compra, $rowsSnapshot = null): void
    {
        if (is_array($rowsSnapshot)) {
            // snapshot: cada item ['producto_id'=>..., 'cantidad'=>...]
            foreach ($rowsSnapshot as $item) {
                $productoId = $item['producto_id'] ?? null;
                $cantidad = (int) round($item['cantidad'] ?? 0);
                if ($productoId !== null && $cantidad > 0) {
                    DB::table('productos')
                        ->where('id', $productoId)
                        ->update([
                            'stock' => DB::raw("GREATEST(stock - {$cantidad}, 0)"),
                            'entradas' => DB::raw("GREATEST(entradas - {$cantidad}, 0)"),
                        ]);
                }
            }
            return;
        }

        // si no hay snapshot, usar comportamiento anterior (detalles actuales)
        $rows = $this->getDetalleRows($compra);
        foreach ($rows as $r) {
            [$productoId, $cantidad] = $this->extractProductoAndCantidad($r);
            if ($productoId !== null && $cantidad > 0) {
                $q = (int) round($cantidad);
                DB::table('productos')
                    ->where('id', $productoId)
                    ->update([
                        'stock' => DB::raw("GREATEST(stock - {$q}, 0)"),
                        'entradas' => DB::raw("GREATEST(entradas - {$q}, 0)"),
                    ]);
            }
        }
    }

    /**
     * Obtiene las filas de detalle probando nombres de relación comunes.
     * Retorna una colección (podría ser vacío).
     */
    private function getDetalleRows(Compra $compra)
    {
        $relaciones = ['detalles', 'detallesCompra', 'detalles_compra', 'detalleCompras'];
        foreach ($relaciones as $rel) {
            if (method_exists($compra, $rel)) {
                try {
                    $rows = $compra->{$rel}()->get();
                    if ($rows->isNotEmpty()) {
                        return $rows;
                    }
                } catch (\Throwable $e) {
                    // ignorar y probar siguiente relación
                }
            }
        }
        // si no hay filas, intentar devolver empty collection para evitar null
        return collect();
    }

    /**
     * Extrae id de producto y cantidad de un registro de detalle probando nombres comunes.
     * Retorna [productoId|null, cantidad_float]
     */
    private function extractProductoAndCantidad($r): array
    {
        $qtyCols = ['cantidad', 'qty', 'cantidad_compra', 'cantidad_producto', 'quantity'];
        $prodCols = ['producto_id', 'productoId', 'id_producto', 'product_id', 'producto'];

        $cantidad = 0.0;
        $productoId = null;

        foreach ($qtyCols as $q) {
            if (isset($r->{$q})) {
                $cantidad = (float) $r->{$q};
                break;
            }
        }

        foreach ($prodCols as $p) {
            if (isset($r->{$p})) {
                $productoId = $r->{$p};
                break;
            }
        }

        return [$productoId, $cantidad];
    }

    /**
     * Intento robusto de obtener el estado actual leyendo atributos comunes.
     */
    private function getEstadoActual(Compra $compra): ?string
    {
        $names = ['estado', 'estado_compra', 'estado_documento'];
        foreach ($names as $n) {
            if (isset($compra->{$n})) {
                return $compra->{$n};
            }
        }
        return null;
    }

    /**
     * Intenta obtener el estado original (antes del update) probando nombres comunes.
     */
    private function getOriginalEstado(Compra $compra): ?string
    {
        $names = ['estado', 'estado_compra', 'estado_documento'];
        foreach ($names as $n) {
            // getOriginal existe en modelos Eloquent; si no, devuelve null
            try {
                $orig = $compra->getOriginal($n);
                if ($orig !== null) {
                    return $orig;
                }
            } catch (\Throwable $e) {
                // continuar con siguiente nombre
            }
        }
        return null;
    }
}
