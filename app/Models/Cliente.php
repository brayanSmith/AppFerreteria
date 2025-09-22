<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'razon_social',
        'direccion',
        'telefono',
        'ciudad',
        'email',
        'representante_legal',
        'activo',
        'novedad',
        'ruta_id',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }
}
