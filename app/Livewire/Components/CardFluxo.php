<?php

namespace App\Livewire\Components;

use Livewire\Component;

class CardFluxo extends Component
{

    public $readings;
    public $sensor;

    public function render()
    {
        return view('livewire.components.card-fluxo');
    }
}
