<div class="flex h-screen bg-gray-100 dark:bg-neutral-900 font-sans antialiased text-gray-800 dark:text-gray-100">

    <!-- Panel izquierdo -->
    <div class="w-2/3 p-6 flex flex-col">

        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            Products
        </h2>

        <!-- Buscador -->
        <div class="flex-shrink-0 mb-4">
            <input wire:model.live="search" type="text" placeholder="Search products by name or SKU..."
                class="w-full px-5 py-3 border focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors
                       dark:bg-neutral-800 dark:border-blue-700 dark:text-gray-100">

            @if (session()->has('error'))
                <div class="mt-2 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('success'))
                <div
                    class="mt-2 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg shadow-md">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Listado de productos -->
        <div class="flex-grow overflow-y-auto pr-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($this->filteredProducts as $product)
                    <div
                        class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg overflow-hidden
                                transition-all duration-200 transform hover:scale-105 hover:shadow-xl">


                        <div class="p-4">
                            <div class="w-full h-32 bg-gray-200 dark:bg-neutral-700 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                                @if($product->imagen_producto)
                                    <img src="{{ asset('storage/' . $product->imagen_producto) }}" alt="{{ $product->nombre_producto }}" class="object-contain h-32 w-full" />
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sin imagen</span>
                                @endif
                            </div>

                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                                {{ $product->nombre_producto }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                SKU: {{ $product->codigo_producto }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 font-bold">
                                DETAL: {{ number_format($product->valor_detal_producto, 2) }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 font-bold">
                                FERRETERO: {{ number_format($product->valor_ferretero_producto, 2) }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 font-bold">
                                MAYORISTA: {{ number_format($product->valor_mayorista_producto, 2) }}
                            </p>
                        </div>

                        <button wire:click="addToCart({{ $product->id }})"
                            class="w-full py-3 bg-indigo-600 text-white font-bold transition hover:bg-indigo-700 rounded-b-2xl">
                            Add to Cart
                        </button>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400 mt-8">
                        No products found.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div
        class="w-1/3 bg-white dark:bg-neutral-800 border-l dark:border-neutral-700 p-6 flex flex-col shadow-xl overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            Productos agregados: {{ collect($this->cart)->sum('cantidad') }}
        </h2>
        {{-- Productos en el carrito --}}
    <div class="flex-grow pr-2 overflow-y-auto max-h-96">
            @forelse($this->cart as $cartProduct)
                <div
                    class="flex items-center justify-between p-4 mb-4 bg-gray-50 dark:bg-neutral-700 rounded-xl shadow-sm">
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                            {{ $cartProduct['nombre_producto'] }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            SKU: {{ $cartProduct['codigo_producto'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            COP: {{ number_format($this->getPrecioProducto($cartProduct), 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">
                            <span class="font-bold">TOTAL:</span> {{ number_format($this->getPrecioProducto($cartProduct) * $cartProduct['cantidad'], 2) }}
                        </p>

                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="number" min="1"
                            wire:model.live.debounce.500ms="cart.{{ $cartProduct['id'] }}.cantidad"
                            class="py-2.5 sm:py-3 px-4 block w-20 border-gray-200 rounded-lg sm:text-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-neutral-900 dark:border-neutral-700
                               dark:text-neutral-400 dark:placeholder-neutral-500
                               dark:focus:ring-neutral-600">

                        <button wire:click="removeFromCart({{ $cartProduct['id'] }})"
                            class="p-2 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                            ✕
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">Tu carrito está vacío.</p>
            @endforelse
        </div>


        <!-- Checkear carrito -->
        <div class="flex-shrink-0 mt-6 space-y-4">
            <div class="space-y-2">
                <label for="cliente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Cliente
                </label>

                <select wire:model="cliente_id" id="cliente"
                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                   focus:border-blue-500 focus:ring-blue-500
                   dark:bg-neutral-900 dark:border-neutral-700
                   dark:text-neutral-400 dark:placeholder-neutral-500
                   dark:focus:ring-neutral-600">
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ...Metodo de Pago... --}}
            <div class="mt-4">
                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Método de Pago:</span>
                <div class="flex space-x-2">
                    <button type="button" wire:click="$set('metodo_pago', 'A CREDITO')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border
                transition
                {{ $metodo_pago === 'A CREDITO'
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                        A CREDITO
                    </button>
                    <button type="button" wire:click="$set('metodo_pago', 'EFECTIVO')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border
                transition
                {{ $metodo_pago === 'EFECTIVO'
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                        EFECTIVO
                    </button>
                </div>
            </div>
            {{-- ...Tipo de Precio... --}}

            <div class="mt-4">
                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Precio:</span>
                <div class="flex space-x-2">
                    <button type="button" wire:click="$set('tipo_precio', 'FERRETERO')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition
            {{ $tipo_precio === 'FERRETERO'
                ? 'bg-blue-600 text-white border-blue-600'
                : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                        FERRETERO
                    </button>
                    <button type="button" wire:click="$set('tipo_precio', 'MAYORISTA')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition
            {{ $tipo_precio === 'MAYORISTA'
                ? 'bg-blue-600 text-white border-blue-600'
                : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                        MAYORISTA
                    </button>
                    <button type="button" wire:click="$set('tipo_precio', 'DETAL')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition
            {{ $tipo_precio === 'DETAL'
                ? 'bg-blue-600 text-white border-blue-600'
                : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                        DETAL
                    </button>
                </div>
            </div>




            <div class="mt-4">
                <label for="primer_comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primer comentario</label>
                <textarea id="primer_comentario" wire:model="primer_comentario" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-100 mb-2"
                    placeholder="Escribe el primer comentario..."></textarea>
            </div>
            <div class="mt-2">
                <label for="segundo_comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Segundo comentario</label>
                <textarea id="segundo_comentario" wire:model="segundo_comentario" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-100"
                    placeholder="Escribe el segundo comentario..."></textarea>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-neutral-700">

                <div class="flex justify-between items-center mb-2 text-lg font-bold">
                    <span>Total a pagar:</span>
                    <span>COP {{ number_format(num: $this->subtotal, decimals: 2) }}</span>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0 mt-6">

            <button wire:click="checkout" wire:loading.attr="disabled"
                class="w-full py-4 bg-green-600 text-white font-bold text-lg rounded-lg
               transition-colors duration-200 hover:bg-green-700
               disabled:opacity-50 disabled:cursor-not-allowed shadow-lg">
                Complete Sale
            </button>
        </div>



    </div>
</div>


