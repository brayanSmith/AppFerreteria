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
        'ciudad',
        'estado',
        'metodo_pago',
        'tipo_precio',
        'primer_comentario',
        'segundo_comentario',
        'subtotal',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function recalcularTotales(float $tasaImpuesto = 0.0)
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $impuestos = round($subtotal * $tasaImpuesto, 2);
        $total = $subtotal + $impuestos;
        $this->updateQuietly(compact('subtotal', 'impuestos', 'total'));
    }

    //Crear el codigo del pedido despues de crear el pedido
    protected static function booted()
    {
        static::created(function ($pedido) {
            $pedido->codigo = 'PED-' . $pedido->id;
            $pedido->save();
        });
    }
}
