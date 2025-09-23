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

     /**
     * 🔹 Recalcula subtotal del pedido
     */
    public function recalcularTotales(): void
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $this->saveQuietly(); // 👈 evita disparar eventos otra vez
        $this->subtotal = $subtotal;
    }

    protected static function booted()
    {
        static::created(function ($pedido) {
            $pedido->codigo = 'PED-' . $pedido->id;
            $pedido->saveQuietly();
        });
    }
}
