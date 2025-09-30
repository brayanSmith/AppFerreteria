<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Abono extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'fecha',
        'monto',
        'descripcion',
        'imagen',
        'pedido_id',
        'fecha_vencimiento',
        'forma_pago',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_vencimiento' => 'date',
        'monto' => 'decimal:2',
    ];

    public function calcularFechaVencimiento($dias = 30)
    {
        if ($this->fecha) {
            return $this->fecha->addDays($dias);
        }
        return null;
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
