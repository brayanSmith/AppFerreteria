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
        'primer_comentario',
        'segundo_comentario',
        'subtotal',
        'levantar_deuda',
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

    public function recalcularTotales(float $tasaImpuesto = 0.0)
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $this->updateQuietly(compact('subtotal'));
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
