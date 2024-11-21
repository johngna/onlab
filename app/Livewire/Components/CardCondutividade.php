<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\SensorReading;

class CardCondutividade extends Component
{

    public $readings;
    public $sensor;


    public function render()
    {
        return view('livewire.components.card-condutividade');
    }


}
