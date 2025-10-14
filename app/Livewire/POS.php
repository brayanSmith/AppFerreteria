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
use Livewire\WithPagination;

class POS extends Component
{
    use WithPagination;

    // Tema de paginación (Tailwind por Filament)
    protected $paginationTheme = 'tailwind';

    // Modal de confirmación de venta
    public $showConfirmModal = false;
    public $confirmModalTitle = '';
    public $confirmModalBody = '';

    //Propiedades
    public $productos;
    public $clientes = [];
    public $search = '';
    public $cart = [];

    //propiedades para validar
    public $cliente_id = null;

    public $valor_decuento = 0; //
    public $metodo_pago = "CREDITO";
    public $tipo_precio = "DETAL";
    public $tipo_venta = "ELECTRONICA";

    public $valor_producto = 0;
    // Comentarios
    public $primer_comentario = '';
    public $segundo_comentario = '';
    public $ciudad = '';
    public $direccion = '';
    public $cantidad = 1;
    public $perPage = 10;
    public $contador_impresiones = 0;
    public $ciudades = [];
    public $ciudadSeleccionada;
    public $direccionSeleccionada;

    public function mount()
    {

        $this->clientes = Cliente::orderBy('razon_social')->get();
        $this->ciudades = Cliente::select('ciudad')->distinct()->orderBy('ciudad')->pluck('ciudad')->toArray();
    }

    // Actualizar ciudad cuando se selecciona un cliente
    public function updatedClienteId($value): void
{
    // 1) Reset dependientes
    $this->ciudadSeleccionada = '';
    $this->direccionSeleccionada = '';
    if (property_exists($this, 'ciudad')) {
        $this->ciudad = '';
    }
    if (property_exists($this, 'direccion')) {
        $this->direccion = '';
    }

    // 2) Normaliza ID (evita '0', '', 'abc', etc.)
    $id = filter_var($value, FILTER_VALIDATE_INT) ?: null;
    if (!$id) {
        return; // selección vacía o inválida
    }

    // 3) Si ya tienes la colección $clientes, úsala y evita ir a BD
    if (property_exists($this, 'clientes') && !empty($this->clientes)) {
        $c = collect($this->clientes)->firstWhere('id', $id);
        if ($c) {
            $this->ciudadSeleccionada = $c['ciudad'] ?? $c['municipio'] ?? '';
            $this->direccionSeleccionada = $c['direccion'] ?? $c['direccion'] ?? '';
            if (property_exists($this, 'ciudad')) {
                $this->ciudad = $this->ciudadSeleccionada;
            }
            if (property_exists($this, 'direccion')) {
                $this->direccion = $this->direccionSeleccionada;
            }
            return;
        }
    }

    // 4) Fallback: obtener sólo lo necesario de BD
    $cliente = Cliente::query()
        ->select(['id', 'ciudad', 'municipio' ,'direccion'])
        ->find($id);

    if (!$cliente) {
        // Si usas Tom Select / Select2 puedes limpiar el widget en el front:
        // $this->dispatch('reset-cliente-select'); // JS hará ts.clear() / $el.val(null).trigger('change')
        return;
    }

    // 5) Asignar ciudad
     $this->ciudadSeleccionada = $cliente->ciudad ?: $cliente->municipio ?: '';
       $this->direccionSeleccionada = $cliente->direccion ?? $cliente->direccion1 ?? $cliente->direccion_1 ?? '';
        if (property_exists($this, 'ciudad')) {
            $this->ciudad = $this->ciudadSeleccionada;
        }
        if (property_exists($this, 'direccion')) {
            $this->direccion = $this->direccionSeleccionada;
        }
    }



    // Resetear la página cuando cambia el buscador o el tamaño de página
    public function updated($name, $value)
    {
        if (in_array($name, ['search', 'perPage'])) {
            $this->resetPage();
        }
    }



    #[Computed]
    public function filteredProducts()
    {
        return Producto::query()
            ->where('stock', '>', 0)
            ->where('activo', 1)
            ->when(
                $this->search,
                fn($q) =>
                $q->where(
                    fn($qq) =>
                    $qq->where('nombre_producto', 'like', "%{$this->search}%")
                        ->orWhere('codigo_producto', 'like', "%{$this->search}%")
                )
            )
            ->orderBy('nombre_producto')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->cart)->sum(function ($producto) {
            return $this->getPrecioProducto($producto) * $producto['cantidad'];
        });
    }


    // Agregar producto al carrito (comportamiento original)
    public function addToCart($productoId, $cantidad = 2)
    {
        $producto = Producto::find($productoId);
        $inventario = Producto::find($productoId);
        if (!$inventario || $inventario->stock <= 0) {
            Notification::make()
                ->title('Este Proucto esta fuera de Stock!')
                ->danger()
                ->send();
            return;
        }
        if (isset($this->cart[$productoId])) {
            $currentQuantity = $this->cart[$productoId]['cantidad'];
            $nuevaCantidad = $currentQuantity + $cantidad;
            if ($nuevaCantidad > $inventario->stock) {
                Notification::make()
                    ->title("No se pueden agregar más productos. Solo {$inventario->stock} en stock")
                    ->danger()
                    ->send();
                return;
            }
            $this->cart[$productoId]['cantidad'] = $nuevaCantidad;
        } else {
            if ($cantidad > $inventario->stock) {
                Notification::make()
                    ->title("No se pueden agregar más productos. Solo {$inventario->stock} en stock")
                    ->danger()
                    ->send();
                return;
            }
            $this->cart[$productoId]  = [
                'id' => $producto->id,
                'nombre_producto' => $producto->nombre_producto,
                'codigo_producto' => $producto->codigo_producto,
                'valor_detal_producto' => $producto->valor_detal_producto,
                'valor_ferretero_producto' => $producto->valor_ferretero_producto,
                'valor_mayorista_producto' => $producto->valor_mayorista_producto,
                'imagen_producto' => $producto->imagen_producto,
                'cantidad' => $cantidad,
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
                ->title('Este Producto esta fuera de Stock!')
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
        DB::beginTransaction();

        //crear la venta... db
        try {
            //crear la venta
            $pedido = Pedido::create([

                'cliente_id' => $this->cliente_id,
                'estado' => 'PENDIENTE',
                'metodo_pago' => $this->metodo_pago,
                'tipo_precio' => $this->tipo_precio,
                'tipo_venta' => $this->tipo_venta,
                'primer_comentario' => $this->primer_comentario,
                'segundo_comentario' => $this->segundo_comentario,
                'subtotal' => $this->subtotal(),
                //'ciudad' => $this->ciudad,
                'ciudad' => $this->ciudadSeleccionada,
                //vamoa haacer que la fecha de vencimiento sea 30 dias despues de la fecha actual
                'fecha_vencimiento' => now()->addDays(30)->toDateString(),

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
                $inventario = Producto::find($producto['id']);
                if ($inventario) {
                    $inventario->stock -= $producto['cantidad'];
                    $inventario->save();
                }
            }
            DB::commit();


            //reset cart
            $this->cart = [];

            //resetear otras propiedades
            $this->search = '';
            $this->cliente_id = null;
            $this->metodo_pago = "CREDITO";
            $this->tipo_precio = "DETAL";
            $this->tipo_venta = "ELECTRONICA";
            $this->primer_comentario = '';
            $this->segundo_comentario = '';
            $this->ciudadSeleccionada = '';

            // Guardar la URL del PDF en la sesión para mostrar el botón en la modal
            session(['pedido_pdf_url' => route('pedidos.pdf.download', $pedido->id)]);
            $this->showConfirmModal = true;
            $this->confirmModalTitle = '¡Venta exitosa!';
            $this->confirmModalBody = 'El pedido fue ingresado exitosamente.';

            // 🚀 Cerrar la modal del carrito
            $this->dispatch('cerrar-modal-carrito');


            // Limpiar la URL de PDF de la sesión después de mostrar la modal
        } catch (Exception $th) {
            DB::rollBack();
            session()->forget('pedido_pdf_url');
            Notification::make()
                ->title('Error al registrar')
                ->body('Error al completar la venta, intentelo de nuevo.\n' . $th->getMessage())
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
