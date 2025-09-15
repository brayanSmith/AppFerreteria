<?php

namespace App\Filament\Pages;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

use Filament\Pages\Page;

class Pos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected string $view = 'filament.pages.pos';
}
