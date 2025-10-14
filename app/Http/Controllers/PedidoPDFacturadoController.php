<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPDFacturadoController extends Controller
{
    public function download($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.pedidoFacturado', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        return $pdf->download("pedidoFacturado_{$pedido->id}.pdf");
    }

    public function stream($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.pedidoFacturado', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
        ]);

        return $pdf->stream("pedido_Facturado{$pedido->id}.pdf");
    }
}
