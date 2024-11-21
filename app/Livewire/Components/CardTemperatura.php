<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\SensorReading;

class CardTemperatura extends Component
{

    public $readings;
    public $sensor;


    public function render()
    {

        return view('livewire.components.card-temperatura');
    }



}
