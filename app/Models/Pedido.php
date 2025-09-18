<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'cliente_id',
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

    public function recalcularTotales(float $tasaImpuesto = 0.0){
        $subtotal = $this->detalles()->sum('subtotal');
        $impuestos = round($subtotal * $tasaImpuesto, 2);
        $total = $subtotal + $impuestos;
        $this->updateQuietly(compact('subtotal', 'impuestos', 'total'));
    }
}
