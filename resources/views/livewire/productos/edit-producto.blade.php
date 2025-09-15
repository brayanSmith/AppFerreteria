<div>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button type="submit" icon="heroicon-m-sparkles" class="mt-4">
            Editar Producto
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
