<?php

namespace App\Livewire;

use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Counter extends Component implements HasForms
{
    public $count = 1;

    public function increment()
    {
        $this->count++;
    }
    public function decrement()
    {
        $this->count--;
    }
    public function render()
    {
        return view('livewire.counter');
    }
}
