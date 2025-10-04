<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'codigo',
        'cliente_id',
        'fecha',
        'fecha_sola',
        'ciudad',
        'estado',
        'metodo_pago',
        'tipo_precio',
        'tipo_venta',
        'primer_comentario',
        'segundo_comentario',
        'subtotal',
        'en_cartera',
        'abono',
        'restante',
        'descuento',
        'total_general',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'fecha_sola' => 'date',
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
    public function abonoPedido()
    {
        return $this->hasOne(Abono::class);
    }

     /**
     * 🔹 Recalcula subtotal del pedido
     */
    public function recalcularTotales(): void
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $this->saveQuietly(); // 👈 evita disparar eventos otra vez
        $this->subtotal = $subtotal;
    }

    Public function recalcularAbono(): void
    {
        $abonos = $this->abonoPedido()->sum('monto');
        $this->restante = $this->subtotal - $abonos;
        $this->saveQuietly(); // 👈 evita disparar eventos otra vez
    }

    public function recalcularTotalGeneral(): void
    {
        $total_general = $this->detalles()->sum('subtotal');
        $abonos = $this->abonoPedido()->sum('monto');
        $descuento = $this->descuento;
        $this->restante = $total_general - $abonos;
        $this->total_general = $total_general - $descuento;
        $this->saveQuietly(); // 👈 evita disparar eventos otra vez
    }

    protected static function booted()
    {
        static::created(function ($pedido) {
            $pedido->codigo = 'PED-' . $pedido->id;
            $pedido->saveQuietly();
        });
    }
}
