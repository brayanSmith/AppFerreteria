<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use DragonCode\Contracts\Http\Builder;
use Exception;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;


class POS extends Component
{


    //Propiedades
    public $productos;
    public $clientes;
    public $search = '';
    public $cart = [];

    //propiedades para validar
    public $cliente_id = null;
    public $paid_amount = 0;
    public $valor_decuento = 0; //
    public $metodo_pago = "A CREDITO";
    public $tipo_precio = "DETAL";

    public $valor_producto = 0;

    public function mount()
    {
        //cargar todos los productos
        $this->productos = Producto::where('stock', '>', 0)
            ->where('activo', 1)
            ->get();
        //cargar todos los clientes
        $this->clientes = Cliente::all();

        //dd($this->productos, $this->clientes);
    }

    #[Computed]
    public function filteredProducts()
    {
        if (empty($this->search)) {
            return $this->productos;
        }
        return $this->productos->filter(function ($producto) {
            return str_contains(strtolower($producto->nombre_producto), strtolower($this->search))
                || str_contains(strtolower($producto->codigo_producto), strtolower($this->search));
        });
    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->cart)->sum(function($producto) {
            return $this->getPrecioProducto($producto) * $producto['cantidad'];
        });
    }

    //placeholder para tax

    #[Computed]
    public function tax()
    {
        return $this->subtotal * 0.15;
    }


    #[Computed]
    public function totalBeforeDiscount()
    {
        return $this->subtotal + $this->tax;
    }


    #[Computed]
    public function total()
    {
        $discountedTotal = $this->totalBeforeDiscount - $this->valor_decuento;
        return $discountedTotal;
    }


    #[Computed]
    public function change() {}

    // Agregar producto al carrito (comportamiento original)
    public function addToCart($productoId)
    {
        $producto = Producto::find($productoId);
        $inventario = Producto::find($productoId)->first();
        if (!$inventario || $inventario->stock <= 0) {
            Notification::make()
                ->title('Este Proucto esta fuera de Stock!')
                ->danger()
                ->send();
            return;
        }
        if (isset($this->cart[$productoId])) {
            $currentQuantity = $this->cart[$productoId]['cantidad'];
            if ($currentQuantity >= $inventario->stock) {
                Notification::make()
                    ->title("No se pueden agregar mas productos. Solo {$inventario->stock} en stock")
                    ->danger()
                    ->send();
                return;
            }
            $this->cart[$productoId]['cantidad']++;
        } else {
            $this->cart[$productoId]  = [
                'id' => $producto->id,
                'nombre_producto' => $producto->nombre_producto,
                'codigo_producto' => $producto->codigo_producto,
                'valor_detal_producto' => $producto->valor_detal_producto,
                'valor_ferretero_producto' => $producto->valor_ferretero_producto,
                'valor_mayorista_producto' => $producto->valor_mayorista_producto,
                'cantidad' => 1,
            ];
        }
    }
    //remover productos del carro
    public function removeFromCart($productoId)
    {
        unset($this->cart[$productoId]);
    }

    //actualizar la cantidad en el producto del carro por item
    public function updateQuantity($productoId, $cantidad)
    {
        //cuando la cantidad de un item es menor a 1
        $cantidad = max(1, (int) $cantidad);

        //obtener el inventario
        $inventario = Producto::find($productoId)->first();

        if ($cantidad > $inventario->stock) {
            Notification::make()
                ->title('Este Proucto esta fuera de Stock!')
                ->danger()
                ->send();
            $this->cart[$productoId]['stock'] = $inventario->stock;
        } else {
            $this->cart[$productoId]['stock'] = $cantidad;
        }
    }

    //Verificar que el carro no este vacio
    public function checkout()
    {
        //checkear si el carro no esta vacio
        if (empty($this->cart)) {
            Notification::make()
                ->title('Venta Fallida')
                ->body('Tu carro esta vacio')
                ->danger()
                ->send();
            return;
        }
        // validacion basica para el paid_amount (con cuanto paga el cliente)
        /*if ($this->paid_amount < $this->total){
            Notification::make()
            ->title('Venta Fallida')
            ->body('El valor pagado es menor al de la venta')
            ->danger()
            ->send();
            return;
        }*/

        //


        //crear la venta... db
        try {
            DB::beginTransaction();

            //crear la venta
            $pedido = Pedido::create([

                'cliente_id' => $this->cliente_id,
            ]);

            //Crear Productos Vendidos

            foreach ($this->cart as $producto) {
                $precio_unitario = $this->getPrecioProducto($producto);
                $subtotal = $precio_unitario * $producto['cantidad'];
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $precio_unitario,
                    'subtotal' => $subtotal,
                ]);


                //actualizar ek stock
                /*$inventario = Producto::where($producto['id'])->first();
                if ($inventario) {
                    $inventario->stock -= $producto['cantidad'];
                    $inventario->save();
                }*/
            }
            DB::commit();

            //reset cart
            $this->cart = [];

            //resetear otras propiedades
            $this->search = '';
            $this->cliente_id = null;
            $this->paid_amount = 0;

            Notification::make()
                ->title('Pedido Registrado!')
                ->body('El pedido fue ingresado exitosamente!')
                ->success()
                ->send();
        } catch (Exception $th) {
            DB::rollBack();
             Notification::make()
                ->title('Error al Registrar!')
                ->body('Error al completar la venta, intentelo de nuevo')
                ->danger()
                ->send();
        }
    }

    public function getPrecioProducto($producto)
    {
        switch ($this->tipo_precio) {
            case 'FERRETERO':
                return $producto['valor_ferretero_producto'];
            case 'MAYORISTA':
                return $producto['valor_mayorista_producto'];
            case 'DETAL':
            default:
                return $producto['valor_detal_producto'];
        }
    }

    public function render()
    {
        return view('livewire.p-o-s');
    }
}
