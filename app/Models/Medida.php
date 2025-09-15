<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    //
    Use HasFactory;

    protected $fillable = ['nombre_medida', 'descripcion_medida'];

    public function detalleProducciones()
    {
        return $this->hasMany(DetalleProduccion::class);
    }

}
