<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoPDFacturadoController extends Controller
{
    public function download($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);
        $empresa = Empresa::first(); // Obtener el primer registro de empresas

        $pdf = Pdf::loadView('pdf.pedidoFacturado', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles,
            'empresa'  => $empresa,
        ]);

        return $pdf->download("pedidoFacturado_{$pedido->id}.pdf");
    }

    public function stream($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);
        $empresa = Empresa::first(); // Obtener el primer registro de empresas

        $pdf = Pdf::loadView('pdf.pedidoFacturado', [
            'pedido'   => $pedido,
            'cliente'  => $pedido->cliente,
            'detalles' => $pedido->detalles, 
            'empresa'  => $empresa,
        ]);

        return $pdf->stream("pedido_Facturado{$pedido->id}.pdf");
    }
}