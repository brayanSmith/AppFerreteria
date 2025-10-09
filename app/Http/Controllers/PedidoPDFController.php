<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPDFController extends Controller
{
    public function download($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        // incrementar contador de impresiones de forma atómica
        $pedido->increment('contador_impresiones');

        $pdf = Pdf::loadView('pdf.pedido', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        return $pdf->download("pedido_{$pedido->id}.pdf");
    }

    public function stream($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        // incrementar contador de impresiones de forma atómica
        $pedido->increment('contador_impresiones');

        $pdf = Pdf::loadView('pdf.pedido', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        return $pdf->stream("pedido_{$pedido->id}.pdf");
    }
}
