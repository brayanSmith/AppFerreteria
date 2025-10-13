<div>
    <div class="mb-3">
        <label for="client-search" class="form-label">Buscar Cliente</label>
        <select class="form-control" wire:model.live="cliente_id">
            <option value="">Seleccione un cliente</option>
            @foreach ( $clientes as $cliente )
                <option value="{{ $cliente->id }}">{{ $cliente->razon_social }} - {{ $cliente->ciudad }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        {{-- Vamos hacer que una caja de texto se llene con la ciudad del cliente seleccionado --}}
        <input type="text" id="city-filter" class="form-control" wire:model="ciudadSeleccionada" readonly>
    </div>
</div>

