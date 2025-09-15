<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleProduccion extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'produccion_id',
        'producto_id',
        'medida_id',
        'fecha_produccion',
        'observaciones',
        'cantidad',
        'lote',
    ];

    public function produccion()
    {
        return $this->belongsTo(Produccion::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function medida()
    {
        return $this->belongsTo(Medida::class);
    }


}
