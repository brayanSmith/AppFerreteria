<div>

    <div x-data="{ open: false }" class="relative">
        <!-- Trigger / botón flotante -->
        <button @click="open = true"
            class="rounded-full flex items-center gap-2 px-4 py-2 fixed bottom-8 right-8 z-50 shadow-lg bg-info-600 text-white hover:bg-info-700 focus:outline-none focus:ring-2 focus:ring-info-500"
            style="min-width: 64px; min-height: 48px;">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m6-9v9m2-9l2 9" />
            </svg>
            <span class="ml-1 font-bold">({{ collect($this->cart)->sum('cantidad') }})</span>
        </button>

        <!-- Modal -->
        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-start justify-end" style="display: none;">
            <!-- backdrop -->
            <div @click="open = false" class="absolute inset-0 bg-black/40"></div>

            <!-- panel slide-over -->
            <div
                class="relative bg-white dark:bg-neutral-800 rounded-l-2xl shadow-2xl w-full max-w-md h-full overflow-auto p-6 z-50">
                <!-- Close button arriba -->
                <div class="flex justify-end">
                    <button @click="open = false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white text-2xl p-1"
                        aria-label="Cerrar">&times;</button>
                </div>

                {{-- Modal content --}}
                <div class="space-y-6 max-w-full ">
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
                                        COP: {{ number_format($this->getPrecioProducto($cartProduct), 0) }}

                                        <span class="font-bold">| TOTAL:</span>
                                        {{ number_format($this->getPrecioProducto($cartProduct) * $cartProduct['cantidad'], 0) }}
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

                             @include('livewire.partials.searchable-client-select', [
                                'cliente_id' => $cliente_id,
                                'ciudad' => $ciudad,
                                'clientes' => $clientes,
                            ])
                        </div>

                        {{-- ...Metodo de Pago... --}}
                        <div class="mt-4">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Método de
                                Pago:</span>
                            <div class="flex space-x-2">
                                <button type="button" wire:click="$set('metodo_pago', 'CREDITO')"
                                    class="px-4 py-2 rounded-full text-sm font-semibold border
                        transition
                        {{ $metodo_pago === 'CREDITO'
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                                    CRÉDITO
                                </button>
                                <button type="button" wire:click="$set('metodo_pago', 'CONTADO')"
                                    class="px-4 py-2 rounded-full text-sm font-semibold border
                        transition
                        {{ $metodo_pago === 'CONTADO'
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                                    CONTADO
                                </button>
                            </div>
                        </div>
                        {{-- ...Tipo de Precio... --}}

                        <div class="mt-4">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de
                                Precio:</span>
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

                        {{-- ...Tipo de Venta... --}}
                        <div class="mt-4">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de
                                Venta:</span>
                            <div class="flex space-x-2">
                                <button type="button" wire:click="$set('tipo_venta', 'ELECTRONICA')"
                                    class="px-4 py-2 rounded-full text-sm font-semibold border transition
                    {{ $tipo_venta === 'ELECTRONICA'
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                                    ELECTRÓNICA
                                </button>
                                <button type="button" wire:click="$set('tipo_venta', 'REMISIONADA')"
                                    class="px-4 py-2 rounded-full text-sm font-semibold border transition
                    {{ $tipo_venta === 'REMISIONADA'
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-neutral-600 hover:bg-blue-100 dark:hover:bg-blue-900' }}">
                                    REMISIONADA
                                </button>
                            </div>
                        </div>

                        {{-- Comentarios --}}

                        <div class="mt-4">
                            <label for="primer_comentario"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primer
                                comentario</label>
                            <textarea id="primer_comentario" wire:model="primer_comentario" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-100 mb-2"
                                placeholder="Escribe el primer comentario..."></textarea>
                        </div>
                        <div class="mt-2">
                            <label for="segundo_comentario"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Segundo
                                comentario</label>
                            <textarea id="segundo_comentario" wire:model="segundo_comentario" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-100"
                                placeholder="Escribe el segundo comentario..."></textarea>
                        </div>
                        {{-- -Bodegas
                        <div class="mt-4">
                            <label for="bodega_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bodega</label>
                            <select id="bodega_id" wire:model="bodegaSeleccionada"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-100">
                                <option value="">Selecciona una bodega</option>
                                @foreach($bodegas as $bodega)
                                    <option value="{{ $bodega->id }}">{{ $bodega->nombre_bodega }}</option>
                                @endforeach
                            </select>
                        </div>--}}

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-neutral-700">

                            <div class="flex justify-between items-center mb-2 text-lg font-bold">
                                <span>Total a Pagar:</span>
                                <span>COP {{ number_format(num: $this->subtotal(), decimals: 0) }}</span>
                            </div>

                        </div>
                    </div>

                    <div class="flex-shrink-0 mt-6">

                        <button wire:click="checkout" wire:loading.attr="disabled"
                            class="w-full py-4 bg-green-600 text-white font-bold text-lg rounded-lg
                       transition-colors duration-200 hover:bg-green-700
                       disabled:opacity-50 disabled:cursor-not-allowed shadow-lg">
                            Finalizar Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de venta (Alpine/Filament) -->
    <div x-data="{ show: @entangle('showConfirmModal') }" x-show="show" x-transition class="fixed inset-0 z-50 flex items-center justify-center"
        style="display: none;">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div
            class="relative bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl w-full max-w-sm mx-auto p-8 z-50 flex flex-col items-center">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-900 dark:text-gray-100">{{ $confirmModalTitle }}
            </h2>
            <p class="mb-6 text-center text-gray-700 dark:text-gray-200">{{ $confirmModalBody }}</p>
            <div class="flex gap-4">
                @if (session('pedido_pdf_url'))
                    <a href="{{ session('pedido_pdf_url') }}" target="_blank"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">Descargar
                        PDF</a>
                @endif
                <button @click="show = false" wire:click="$set('showConfirmModal', false)"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700">Cerrar</button>
            </div>
        </div>
    </div>

    <div class="flex h-screen bg-gray-100 dark:bg-neutral-900 font-sans antialiased text-gray-800 dark:text-gray-100">

        <!-- Panel izquierdo -->
        <div class="w-full px-1 py-6 md:px-6 flex flex-col">

            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                Productos
            </h2>

            <!-- Buscador -->
            <div class="flex-shrink-0 mb-4">
                <input wire:model.live="search" type="text" placeholder="Buscar productos por nombre o SKU..."
                    class="w-full px-5 py-3 border focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors
                       dark:bg-neutral-800 dark:border-blue-700 dark:text-gray-100">

                @if (session()->has('error'))
                    <div
                        class="mt-2 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg shadow-md">
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
            <div class="flex-grow overflow-y-auto pr-1">
                @php($products = $this->filteredProducts) {{-- paginator --}}
                <div class="grid grid-cols-1 gap-2 md:gap-6">
                    @forelse($products as $product)
                        <div wire:key="prod-{{ $product->id }}"
                            class="bg-grey-100 dark:bg-neutral-800 rounded-2xl shadow-lg">
                            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg overflow-hidden transition-all duration-200 transform hover:scale-105 hover:shadow-xl p-2 md:p-4">

                                <!-- Encabezado: Nombre del producto -->
                                <div class="w-full mb-2">
                                    {{--<p class="font-semibold text-gray-900 dark:text-gray-100 break-words text-sm md:text-base w-full">--}}
                                         <p class="text-xs md:text-sm text-gray-700 dark:text-gray-300 mt-1 font-bold">
                                        {{ $product->nombre_producto }}
                                    </p>
                                </div>

                                <!-- Flex fila en móvil, grid en desktop -->
                                <div class="flex flex-row items-center gap-3 md:grid md:grid-cols-12 md:gap-4">
                                    <!-- Imagen -->
                                    <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-gray-200 dark:bg-neutral-700 rounded-lg overflow-hidden md:col-span-3">
                                        @if ($product->imagen_producto)
                                            <img src="{{ asset('storage/' . $product->imagen_producto) }}"
                                                alt="{{ $product->nombre_producto }}"
                                                class="object-cover w-full h-full md:object-contain" />
                                        @else
                                            <span class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Sin imagen</span>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 md:col-span-6 ml-2 md:ml-0">
                                        <p class="text-[10px] md:text-xs text-gray-500 dark:text-gray-400 mt-1">SKU:
                                            {{ $product->codigo_producto }}</p>
                                        <p class="text-[10px] md:text-xs text-gray-800 dark:text-gray-400 mt-1">DETAL:
                                            {{ number_format($product->valor_detal_producto, 0) }}</p>
                                        <p class="text-[10px] md:text-xs text-gray-800 dark:text-gray-400 mt-1">FERRETERO:
                                            {{ number_format($product->valor_ferretero_producto, 0) }}</p>
                                        <p class="text-[10px] md:text-xs text-gray-800 dark:text-gray-400 mt-1">MAYORISTA:
                                            {{ number_format($product->valor_mayorista_producto, 0) }}</p>
                                    </div>

                                    <!-- Botón -->
                                    <div class="flex items-center justify-center md:col-span-3 mt-0 w-auto">
                                        <div x-data="{ open: false, cantidad: 1 }" class="w-full">
                                            <x-filament::button @click="open = true; cantidad = 1"
                                                class="w-full py-2 px-4 font-bold rounded-lg text-xs md:text-base flex items-center justify-center"
                                                color="primary"
                                                size="md">
                                                <!-- Ícono "+" solo en móvil -->
                                                <svg class="w-5 h-5 md:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                <!-- Texto solo en escritorio -->
                                                <span class="hidden md:block">Agregar</span>
                                            </x-filament::button>

                                            <!-- Modal de cantidad -->
                                            <div x-show="open" x-transition
                                                class="fixed inset-0 z-40 flex items-center justify-center"
                                                style="display: none;">
                                                <div @click="open = false"
                                                    class="absolute inset-0 bg-white/40 dark:bg-neutral-900/40 backdrop-blur-sm transition-all">
                                                </div>
                                                <div
                                                    class="relative bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl w-full max-w-xs mx-auto p-6 z-50">
                                                    <button @click="open = false"
                                                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white text-2xl">&times;</button>
                                                    <div class="mb-4 flex items-end gap-2">
                                                        <div class="flex-1">
                                                            <label
                                                                class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad</label>
                                                            <input type="number" min="1"
                                                                :max="{{ $product->stock ?? 1000 }}"
                                                                x-model.number="cantidad"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-gray-100 text-xs md:text-base" />
                                                        </div>
                                                        <button
                                                            @click="$wire.addToCart({{ $product->id }}, cantidad); open = false"
                                                            class="py-2 px-4 bg-indigo-600 text-white font-bold rounded-lg transition hover:bg-indigo-700 whitespace-nowrap text-xs md:text-base">
                                                            Agregar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- x-data -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500 dark:text-gray-400 mt-8">No products found.
                        </p>
                    @endforelse
                </div>


            </div>
            <!-- Paginación Filament -->
                <div class="mt-6">
                    <x-filament::pagination :paginator="$products" :page-options="[5, 10, 20, 50, 'all']" current-page-option-property="perPage"
                        {{-- ← sin dos puntos --}} extreme-links />

                </div>
        </div>
    </div>
</div>
