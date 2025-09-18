<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPDFController extends Controller
{
    public function download($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

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

        $pdf = Pdf::loadView('pdf.pedido', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        // Abrir en navegador
        return $pdf->stream("pedido_{$pedido->id}.pdf");
    }
}
