@php
    $id = 'select_client_' . \Illuminate\Support\Str::random(8);
    $model = $model ?? 'cliente_id';
    $placeholder = $placeholder ?? 'Seleccione un cliente';

    // Clases Tailwind que quieres aplicar (las dejo literales para que Tailwind las detecte en el build)
    $tailwindClasses = 'py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600';
@endphp

@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js" defer></script>
@endonce

<div wire:ignore class="relative">
    <select id="{{ $id }}" wire:model="{{ $model }}" class="w-full rounded-lg bg-transparent {{ $tailwindClasses }}">
        <option value="">{{ $placeholder }}</option>
        @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
        @endforeach
    </select>
</div>

<style>
    /* Refuerzo: cuando marcamos el contenedor con .ts-dark, forzamos que los elementos internos hereden colores/bordes */
    .ts-dark .ts-control,
    .ts-dark .ts-control input,
    .ts-dark .ts-dropdown {
        background: inherit !important;
        color: inherit !important;
        border-color: none !important;
    }

    /* Asegurar que el placeholder y texto del input interno usen el color */
    .ts-dark .ts-control input::placeholder {
        color: inherit !important;
        opacity: 0.8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function init(){
            if (typeof TomSelect === 'undefined') {
                return setTimeout(init, 50);
            }

            const el = document.getElementById(@json($id));
            if (!el) return;

            const ts = new TomSelect(el, {
                create: false,
                allowEmptyOption: true,
                sortField: { field: 'text', direction: 'asc' },
                onChange(value) {
                    // Dispara eventos para que Livewire detecte el cambio
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            function applyTailwindClasses(){
                try {
                    const classes = @json(explode(' ', trim($tailwindClasses)));
                    const wrapper = el.nextElementSibling; // TomSelect pone el control justo después del select
                    if (!wrapper) return;

                    // Añadir clases al wrapper principal
                    classes.forEach(c => {
                        if (c && !wrapper.classList.contains(c)) {
                            wrapper.classList.add(c);
                        }
                    });

                    // Elementos internos: control, input y dropdown
                    const control = wrapper.querySelector('.ts-control') || wrapper;
                    const controlInput = control ? control.querySelector('input') : null;
                    const dropdown = wrapper.querySelector('.ts-dropdown');

                    if (control) {
                        classes.forEach(c => {
                            if (c && !control.classList.contains(c)) control.classList.add(c);
                        });
                    }

                    if (controlInput) {
                        classes.forEach(c => {
                            if (c && !controlInput.classList.contains(c)) controlInput.classList.add(c);
                        });
                    }

                    if (dropdown) {
                        classes.forEach(c => {
                            if (c && !dropdown.classList.contains(c)) dropdown.classList.add(c);
                        });
                    }

                    // Aplicar clase ts-dark si la página está en dark
                    applyDarkClassTo(wrapper);
                } catch (e) {
                    // silent
                }
            }

            function applyDarkClassTo(wrapper) {
                try {
                    const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
                    if (!wrapper) return;
                    if (isDark) {
                        wrapper.classList.add('ts-dark');
                    } else {
                        wrapper.classList.remove('ts-dark');
                    }
                } catch (e) {}
            }

            // Inicial
            applyTailwindClasses();

            // Livewire re-render: resync valor y reaplicar clases
            if (window.Livewire) {
                Livewire.hook('message.processed', () => {
                    try {
                        const v = el.getAttribute('value') || el.value;
                        if (ts.getValue() != v) {
                            ts.setValue(v);
                        }
                    } catch (e) {}
                    // reaplicar clases por si Livewire re-creó el DOM
                    applyTailwindClasses();
                });
            }

            // Observador para detectar cambios en la clase 'dark' en <html> o <body>
            const observer = new MutationObserver(() => {
                const wrapper = el.nextElementSibling;
                if (wrapper) applyDarkClassTo(wrapper);
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

        })();
    });
</script>
