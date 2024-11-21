<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SensorReading;
use App\Services\AuvoService;
use Illuminate\Http\Request;

class Equipamento extends Component
{


    public $readings;
    public $equipamento;
    public $cliente;
    public $integra = false;


    public function mount(Request $request)
    {

        $auvoService = new AuvoService();
        $this->equipamento = $auvoService->getEquipament(request()->route('numero_serie'));

        $this->cliente = $auvoService->getClient($this->equipamento['associatedCustomerId']);

        $this->readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();
        // $this->readings = SensorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();

        $qtd = count($this->readings);

        if($qtd > 0){
            $this->integra = true;
            $this->dispatch('InitializeChartData', $this->readings);
        }


    }

    public function render()
    {
        return view('livewire.equipamento');
    }

    public function fetchEquipmentData()
    {
        // Lógica para obter os dados do equipamento
        $this->readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();

        $this->dispatch('refreshChartData', $this->readings);

    }
}
