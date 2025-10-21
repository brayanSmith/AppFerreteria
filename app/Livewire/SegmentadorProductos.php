<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Subcategoria;

class SegmentadorProductos extends Component
{

     public $categorias = [];
    public $subcategorias = [];
    public $categoriaSeleccionada = null;
    public $subcategoriaSeleccionada = null;

    public function mount()
    {
        $this->categorias = Categoria::all();
    }

    public function updatedCategoriaSeleccionada($id)
    {
        $this->subcategorias = Subcategoria::where('categoria_id', $id)->get();
        $this->subcategoriaSeleccionada = null;
    }
    public function render()
    {
        return view('livewire.segmentador-productos');
    }
}
