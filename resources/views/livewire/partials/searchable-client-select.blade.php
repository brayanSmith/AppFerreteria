<div>
    <div class="mb-3" wire:ignore>
        {{-- <label class="form-label">Buscar Cliente</label>- --}}
        <select id="client-search" class="form-control" placeholder="Buscar cliente...">
            <option value="">Seleccione un cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}">
                    {{ $cliente->razon_social }} - {{ $cliente->ciudad }}
                </option>
            @endforeach
        </select>
    </div>


     <div class="mb-3">
        <label class="form-label">Ciudad</label>
        <input type="text"
               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                      placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                      dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 dark:placeholder-gray-500"
               wire:model="ciudadSeleccionada"
               readonly>
    </div>

    <script>
        // Reintenta la inicialización en eventos típicos de Livewire/Filament
        document.addEventListener('DOMContentLoaded', initTomSelect, { once: true });
        document.addEventListener('livewire:initialized', initTomSelect);
        document.addEventListener('livewire:navigated', initTomSelect);
        document.addEventListener('livewire:update', initTomSelect);

        function initTomSelect() {
            const sel = document.getElementById('client-search');
            if (!sel || sel.tomselect || typeof TomSelect === 'undefined') return;

            const ts = new TomSelect(sel, {
                allowEmptyOption: true,
                searchField: ['text'],   // busca por el texto visible
                maxOptions: 10000,
                placeholder: sel.getAttribute('placeholder') || 'Buscar...',
                sortField: { field: 'text', direction: 'asc' },
            });

            // Sincroniza -> Livewire
            ts.on('change', (value) => {
                const comp = sel.closest('[wire\\:id]');
                if (comp && window.Livewire) {
                    window.Livewire.find(comp.getAttribute('wire:id'))
                        .set('cliente_id', value || null);
                }
            });

            // (Opcional) si cambias cliente_id desde PHP y quieres reflejarlo en el select:
            window.addEventListener('cliente-set', (e) => ts.setValue(e.detail ?? ''));
        }
    </script>
</div>
