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
        $this->subtotal = ($this->cantidad ?? 0) * ($this->precio_unitario ?? 0);

        // importante: no dispares eventos infinitos
        $this->saveQuietly();
    }

    protected static function booted()
    {
        // cada vez que se cree o actualice un detalle
        static::saving(function (DetallePedido $detalle) {
            $detalle->subtotal = ($detalle->cantidad ?? 0) * ($detalle->precio_unitario ?? 0);
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

