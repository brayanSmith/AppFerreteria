<div class="grid grid-cols-2 gap-4 w-full not-prose">
    <x-filament::section heading="Contador Izquierdo">
        <h1 class="text-xl font-bold">{{ $count }}</h1>
        <div class="flex gap-2 mt-2">
            <x-filament::button color="success" wire:click="increment">+</x-filament::button>
            <x-filament::button color="danger" wire:click="decrement">-</x-filament::button>
        </div>
    </x-filament::section>

    <x-filament::section heading="Contador Derecho">
        <h1 class="text-xl font-bold">{{ $count }}</h1>
        <div class="flex gap-2 mt-2">
            <x-filament::button color="success" wire:click="increment">+</x-filament::button>
            <x-filament::button color="danger" wire:click="decrement">-</x-filament::button>
        </div>
    </x-filament::section>
</div>
