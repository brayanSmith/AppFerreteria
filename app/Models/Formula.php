<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    //
    use HasFactory;
    protected $fillable = ['nombre_formula', 'descripcion_formula'];

    public function producciones()
    {
        return $this->hasMany(Produccion::class);
    }

}
