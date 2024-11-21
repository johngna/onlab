<?php

namespace App\Livewire\Components;

use Livewire\Component;

class CardGalao extends Component
{

    public $readings;
    public $tipo = 1;
    public $sensor;

    public function render()
    {
        return view('livewire.components.card-galao');
    }
}
