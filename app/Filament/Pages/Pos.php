<?php

namespace App\Filament\Pages;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

use Filament\Pages\Page;

class Pos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return 'Pos';
    }

    //vamos hacer que el titulo no se muestre
    public function getTitle(): string
{
    return '';
}
    /*public static function getPluralLabel(): string
    {
        return 'Pedidos Anulados';
    }*/

    protected string $view = 'filament.pages.pos';
}
