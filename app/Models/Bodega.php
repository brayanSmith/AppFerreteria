<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;
    //
    protected $fillable = ['nombre_bodega', 'ubicacion_bodega'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

}
