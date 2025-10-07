<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPDFController extends Controller
{
    public function download($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        // Marcar que se imprimió/descargó al menos una vez (evita listar dos veces)
        if (empty($pedido->contador_impresiones) || $pedido->contador_impresiones < 1) {
            $pedido->contador_impresiones = 1;
            $pedido->saveQuietly();
        }

        $pdf = Pdf::loadView('pdf.pedido', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        // Forzar descarga
        return $pdf->download("pedido_{$pedido->id}.pdf");
    }

    public function stream($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        // Marcar impresión/descarga si aún no está marcada
        if (empty($pedido->contador_impresiones) || $pedido->contador_impresiones < 1) {
            $pedido->contador_impresiones = 1;
            $pedido->saveQuietly();
        }

        $pdf = Pdf::loadView('pdf.pedido', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        // Abrir en navegador
        return $pdf->stream("pedido_{$pedido->id}.pdf");
    }
}
