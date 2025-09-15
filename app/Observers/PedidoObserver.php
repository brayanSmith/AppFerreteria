<?php

namespace App\Observers;

use App\Models\Pedido;

class PedidoObserver
{
    //
    public function saved(Pedido $pedido) {
        $pedido->recalcularTotales(0.0); //ajustar iva si aplica
        //si se marca como pagado y aun no se desconto stock
        if ($pedido->wasChanged('estado') && $pedido->estado === 'pagado'){
            foreach($pedido->detalles as $linea){
                $producto = $linea->producto()->lockForUpdate()->first();
                if ($producto && $producto->stock >= $linea->cantidad){
                    $producto->decrement('stock', $linea->cantidad);
                } else {
                    throw new \RuntimeException("Stock insuficiente para el producto ID {$producto->nombre_producto}");
                }
            }
        }
    }
}
