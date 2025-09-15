<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'cantidad',
        'lote',
        'fecha_produccion',
        'fecha_caducidad',
        'Observaciones',
        'formula_id',
    ];
    public function formula()
    {
        return $this->belongsTo(Formula::class);
    }
    public function detalleProducciones()
    {
        return $this->hasMany(DetalleProduccion::class);
    }

}
