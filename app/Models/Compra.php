<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    /** @use HasFactory<\Database\Factories\CompraFactory> */
    use HasFactory;
    protected $fillable = [
        'factura',
        'proveedor_id',
        'fecha',
        'dias_plazo_vencimiento',
        'fecha_vencimiento',
        'metodo_pago',
        'estado_pago',
        'tipo_compra',
        'estado',
        'observaciones',
        'subtotal',
        'abono',
        'descuento',
        'total_a_pagar',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class);
    }
    public function abonoCompra()
    {
        return $this->hasMany(AbonoCompra::class);
    }

    /**
     * 🔹 Recalcula subtotal de la compra
     */
    public function recalcularTotalesCompra(): void
    {
        $this->subtotal_compra = $this->detalles()->sum('subtotal');
        $this->total_a_pagar_compra = $this->subtotal_compra - $this->descuento_compra;
        $this->estado_pago_compra = $this->total_a_pagar_compra <= $this->abono_compra ? 'Pagado' : 'Pendiente';
        $this->save();
    }
    /**
     * 🔹 Recalcular el total a pagar de la compra = (subtotal - Abono - descuento)
     */
    public function recalcularTotalAPagarCompra(): void
    {
        $this->total_a_pagar_compra = $this->subtotal_compra - $this->abono_compra - $this->descuento_compra;
        $this->save();
    }

    public function getFechaRecibidoCompra($value)
    {
        if (is_null($value)){
            return null;
        }
        return \Carbon\Carbon::parse($value)->setTimezone('America/Bogota');
    }
}
