<?php

namespace App\Livewire;

use App\Models\Equipamento;
use Livewire\Component;
use App\Models\SensorReading;

class Monitor extends Component
{

    public $readings;
    public $equipamento;


    public function mount()
    {
        $this->readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();
        $this->dispatch('InitializeChartData', $this->readings);

        //obter o equipamento através do id passado na rota
        $this->equipamento = Equipamento::find(request()->route('equipamento'));
    }

    public function render()
    {
        return view('livewire.monitor');
    }

    public function fetchEquipmentData()
    {
        // Lógica para obter os dados do equipamento
        $this->readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();

        $this->dispatch('refreshChartData', $this->readings);

    }


}
