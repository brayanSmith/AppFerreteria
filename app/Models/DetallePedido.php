<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    //
    use HasFactory;

    protected $table = 'detalle_pedidos';
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'iva',
        'subtotal'
    ];
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

     /**
     * 🔹 Recalcula el subtotal del detalle
     */
    public function recalcularSubtotal(): void
    {
        $cantidad = $this->cantidad ?? 0;
        $precioUnitario = $this->precio_unitario ?? 0;
        $ivaPercentage = $this->iva ?? 0;
        
        // Calcular el factor del IVA (ej: si iva = 19, factor = 1.19)
        $factorIva = ($ivaPercentage / 100) + 1;
        
        // Subtotal = Cantidad * (Precio unitario * factor IVA)
        $this->subtotal = $cantidad * ($precioUnitario * $factorIva);

        // importante: no dispares eventos infinitos
        $this->saveQuietly();
    }

    protected static function booted()
    {
        // cada vez que se cree o actualice un detalle
        static::saving(function (DetallePedido $detalle) {
            $cantidad = $detalle->cantidad ?? 0;
            $precioUnitario = $detalle->precio_unitario ?? 0;
            $ivaPercentage = $detalle->iva ?? 0;
            
            // Calcular el factor del IVA (ej: si iva = 19, factor = 1.19)
            $factorIva = ($ivaPercentage / 100) + 1;
            
            // Subtotal = Cantidad * (Precio unitario * factor IVA)
            $detalle->subtotal = $cantidad * ($precioUnitario * $factorIva);
        });

        static::saved(function (DetallePedido $detalle) {
            // actualiza el pedido padre
            $detalle->pedido?->recalcularTotales();
        });

        static::deleted(function (DetallePedido $detalle) {
            // también al borrar, recalculamos el pedido
            $detalle->pedido?->recalcularTotales();
        });
    }
}

