<?php

namespace App\Observers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoObserver
{
    //
    public function saved(Pedido $pedido) {
        $pedido->recalcularTotales(); //ajustar iva si aplica
        //si se marca como pagado y aun no se desconto stock
        /*if ($pedido->wasChanged('estado') && $pedido->estado === 'pagado'){
            foreach($pedido->detalles as $linea){
                $producto = $linea->producto()->lockForUpdate()->first();
                if ($producto && $producto->stock >= $linea->cantidad){
                    $producto->decrement('stock', $linea->cantidad);
                } else {
                    throw new \RuntimeException("Stock insuficiente para el producto ID {$producto->nombre_producto}");
                }
            }
        }*/
    }

    public function updated(Pedido $pedido): void
    {
        $original = $pedido->getOriginal('estado');
        $current = $pedido->estado;
        // 1) (Opcional) asegurarse de que totales están actualizados
        if (method_exists($pedido, 'recalcularTotales')) {
            // recalcula en memoria y persiste si tu método lo hace
            $pedido->recalcularTotales();
            // refrescar para tener valores persistidos en la BD
            $pedido->refresh();
        }
        // 2) Lógica existente de stock (si la tienes)...
        // Si pasa a FACTURADO y aún no se aplicó el ajuste
        if ($original !== 'FACTURADO' && $current === 'FACTURADO' && ! $pedido->stock_retirado) {
            DB::transaction(function () use ($pedido) {
                foreach ($pedido->detalles as $detalle) {
                    $qty = (float) ($detalle->cantidad ?? 0);
                    if ($qty <= 0) continue;

                    // bloquear fila producto para evitar race conditions
                    $producto = Producto::where('id', $detalle->producto_id)->lockForUpdate()->first();
                    if (! $producto) {
                        Log::warning("Producto no encontrado al ajustar stock para pedido {$pedido->id}, producto_id {$detalle->producto_id}");
                        continue;
                    }

                    // política: impedir stock negativo (ajustar a 0) o lanzar excepción si prefieres
                    $nuevo = max(0, ($producto->stock ?? 0) - $qty);
                    $producto->stock = $nuevo;
                    $producto->save();
                }

                // marcar que ya se aplicó el ajuste (usar updateQuietly para evitar loop observer)
                $pedido->updateQuietly(['stock_retirado' => true]);
            });
        }

        // Si se revierte desde FACTURADO hacia otro estado y stock_retirado=true -> revertir stock
        if ($original === 'FACTURADO' && $current !== 'FACTURADO' && $pedido->stock_retirado) {
            DB::transaction(function () use ($pedido) {
                foreach ($pedido->detalles as $detalle) {
                    $qty = (float) ($detalle->cantidad ?? 0);
                    if ($qty <= 0) continue;

                    $producto = Producto::where('id', $detalle->producto_id)->lockForUpdate()->first();
                    if (! $producto) {
                        Log::warning("Producto no encontrado al revertir stock para pedido {$pedido->id}, producto_id {$detalle->producto_id}");
                        continue;
                    }

                    $producto->stock = ($producto->stock ?? 0) + $qty;
                    $producto->save();
                }

                $pedido->updateQuietly(['stock_retirado' => false]);
            });
        }

        // 3) Controlar estado_pago según total a pagar
        // calcular total_a_pagar fiable (usa columna total_a_pagar si existe)
        $totalAPagar = $pedido->total_a_pagar ?? (($pedido->subtotal ?? 0) - ($pedido->abono ?? 0) - ($pedido->descuento ?? 0));
        $epsilon = 0.0001;

        // si está esencialmente en 0 -> marcar SALDADO
        if (round((float) $totalAPagar, 4) <= $epsilon) {
            if (($pedido->estado_pago ?? '') !== 'SALDADO') {
                // updateQuietly evita re-disparar observers recursivamente
                $pedido->updateQuietly(['estado_pago' => 'SALDADO']);
                Log::info("Pedido {$pedido->id} marcado SALDADO automáticamente (total a pagar = 0).");
            }
        } else {
            // si vuelve a deber dinero y está SALDADO, revertir a EN_CARTERA
            if (($pedido->estado_pago ?? '') === 'SALDADO') {
                $pedido->updateQuietly(['estado_pago' => 'EN_CARTERA']);
                Log::info("Pedido {$pedido->id} revertido a EN_CARTERA (total a pagar > 0).");
            }
        }
    }
}
