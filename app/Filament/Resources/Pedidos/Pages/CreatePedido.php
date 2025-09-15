<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Producto;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

     public string $productSearch = '';

    public function getProductsProperty()
    {
        return Producto::query()
            ->where('activo', true)
            ->when($this->productSearch, function ($q) {
                $s = $this->productSearch;
                $q->where(fn ($qq) =>
                    $qq->where('nombre_producto', 'like', "%{$s}%")
                       ->orWhere('sku', 'like', "%{$s}%")
                );
            })
            ->orderBy('nombre_producto')
            ->limit(24)
            ->get();
    }

    public function addProduct(int $productId): void
    {
        $p = Producto::where('activo', true)->findOrFail($productId);

        $state = $this->form->getState();
        $items = $state['detallePedidos'] ?? [];

        // Si ya existe la línea, incrementar cantidad
        $index = collect($items)->search(fn ($i) => (int)($i['producto_id'] ?? 0) === $p->id);

        if ($index !== false) {
            $items[$index]['cantidad'] = (int)($items[$index]['cantidad'] ?? 1) + 1;
            $precio = (float)($items[$index]['precio_unitario'] ?? $p->valor_detal_producto);
            $items[$index]['subtotal'] = round($items[$index]['cantidad'] * $precio, 2);
        } else {
            // Nueva línea
            $items[] = [
                'producto_id'     => $p->id,
                'cantidad'        => 1,
                'precio_unitario' => (float) $p->valor_detal_producto,
                'subtotal'        => (float) $p->valor_detal_producto,
            ];
        }

        // Totales
        $state['detallePedidos'] = array_values($items);
        $subtotal = collect($state['detallePedidos'])->sum(fn ($i) => (float)($i['subtotal'] ?? 0));
        $state['subtotal']  = round($subtotal, 2);
        $state['impuestos'] = 0; // IVA si aplica
        $state['total']     = round($state['subtotal'] + $state['impuestos'], 2);

        $this->form->fill($state);

        Notification::make()->title('Producto agregado')->success()->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $subtotal = collect($data['detallePedidos'] ?? [])
            ->sum(fn ($i) => (float)($i['subtotal'] ?? 0));

        $data['subtotal']  = round($subtotal, 2);
        $data['impuestos'] = 0; // IVA si aplica
        $data['total']     = round($data['subtotal'] + $data['impuestos'], 2);

        return $data;
    }
}
