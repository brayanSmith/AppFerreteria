<div>
    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button type="submit" icon="heroicon-m-sparkles" class="mt-4">
            Crear Producto
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
