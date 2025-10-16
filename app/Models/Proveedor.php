<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    use HasFactory;

    protected $fillable = [
        'nit_proveedor',
        'nombre_proveedor',
        'ciudad_proveedor',
        'direccion_proveedor',
        'telefono_proveedor',
        'tipo_proveedor',
    ];
}
