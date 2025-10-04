<?php

namespace App\Forms\Components;

use Filament\Forms\Components\TextInput;

class MoneyInpute extends TextInput
{
    protected string $view = 'filament.forms.components.money-inpute';

    /**
     * Create a new MoneyInput instance.
     * Signature compatible with Field::make(?string $name = null): static
     *
     * @param string|null $name
     * @return static
     */
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->extraAttributes(['class' => 'filament-money-inpute']);
    }

    // Puedes añadir helpers adicionales si los necesitas
}
