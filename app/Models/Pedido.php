<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'dias_plazo_vencimiento',
        'fecha_vencimiento',
        'ciudad',
        'estado',
        'metodo_pago',
        'tipo_precio',
        'tipo_venta',
        'primer_comentario',
        'segundo_comentario',
        'subtotal',
        'en_cartera',
        'abono',
        'descuento',
        'flete',        
        'total_a_pagar',
        'contador_impresiones',
        'impresa',
        'estado_pago',
        'stock_retirado',
        'bodega_id',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'datetime',

    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
    public function abonoPedido()
    {
        return $this->hasOne(Abono::class);
    }
    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


     /**
     * 🔹 Recalcula subtotal del pedido
     */
    public function recalcularTotales(): void
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $this->subtotal = $subtotal;
        // Recalcular totales dependientes
        $this->recalcularTotalAPagar();
    }

    public function recalcularAbono(): void
    {
        $abonos = $this->abonoPedido()->sum('monto');
        $this->abono = $abonos;
        // Recalcular el total a pagar teniendo en cuenta descuento y abonos
        $this->recalcularTotalAPagar();
    }

    public function aplicarDescuento(float $monto): void
    {
        $this->descuento = $monto;
        // Recalcular el total a pagar después de aplicar descuento
        $this->recalcularTotalAPagar();
    }

    /**
     * Recalcula y guarda el campo `total_a_pagar`.
     *
     * Lógica: total_a_pagar = (subtotal + flete) - (abonos + descuento)
     */
    public function recalcularTotalAPagar(): void
    {
        $abonos = $this->abonoPedido()->sum('monto') ?? 0;

        $subtotal = (float) ($this->subtotal ?? 0);
        $flete = (float) ($this->flete ?? 0);
        $descuento = (float) ($this->descuento ?? 0); 

        // Total a Pagar = (Subtotal + Flete) - (Abono + Descuento)
        $calculated = ($subtotal + $flete) - ($abonos + $descuento);
        $this->total_a_pagar = $calculated < 0 ? 0 : $calculated;
        
        // Guardamos también el acumulado de abonos en el campo `abono`
        $this->abono = $abonos;

        $this->saveQuietly(); // evita disparar eventos otra vez
    }

     /**
     * The "booted" method of the model.
     *
     * Here we set the 'codigo' field after the model is created.
     */


    protected static function booted()
    {
        static::created(function ($pedido) {
            $pedido->codigo = 'PED-' . $pedido->id;
            $pedido->saveQuietly();
        });
    }

    // Atributo: devolver fecha en America/Bogota
    public function getFechaAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        return \Carbon\Carbon::parse($value)->setTimezone('America/Bogota');
    }
}
