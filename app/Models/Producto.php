<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'codigo_producto',
        'nombre_producto',
        'descripcion_producto',
        'categoria_id',
        'sub_categoria_id',
        'costo_producto',
        'valor_detal_producto',
        'valor_mayorista_producto',
        'valor_ferretero_producto',
        'imagen_producto',
        'bodega_id',
        'stock',
        'activo',
        'tipo_producto',
        'peso_producto',
        'ubicacion_producto',
        'alerta_producto',
        'empaquetado_externo',
        'empaquetado_interno',
        'referencia_producto',
        'codigo_cliente',
        'volumen_producto',
    ];

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function enStock($cantidad)
    {
        return $this->stock >= $cantidad;
    }


    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function subCategoria()
    {
        return $this->belongsTo(SubCategoria::class, 'sub_categoria_id');
    }

    public function detalleProducciones()
    {
        return $this->hasMany(DetalleProduccion::class);
    }

    public function getPrecioPorTipo(string $tipo): float
    {
        return match ($tipo) {
            'MAYORISTA' => $this->valor_mayorista_producto ?? 0,
            'FERRETERO' => $this->valor_ferretero_producto ?? 0,
            default     => $this->valor_detal_producto ?? 0,
        };
    }
}
