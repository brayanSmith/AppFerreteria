<?php

namespace App\Livewire;

use Filament\Forms\Contracts\HasForms as Formee;
use Livewire\Component;

class Counter extends Component implements Formee
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
