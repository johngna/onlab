<?php

namespace App\Livewire;

use App\Models\AlertReading;
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
    public $avisos = [];


    public function mount(Request $request)
    {

        $auvoService = new AuvoService();
        $this->equipamento = $auvoService->getEquipament(request()->route('numero_serie'));

        $this->cliente = $auvoService->getClient($this->equipamento['associatedCustomerId']);

        $this->readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();
        // $this->readings = SensorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();

        $this->avisos = AlertReading::orderBy('created_at', 'desc')->first();
        // $this->avisos = AlertReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();



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
        $readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();
        $avisos = AlertReading::orderBy('created_at', 'desc')->first();

        //verifica se houve alteração nos dados
        if($readings != $this->readings){
            $this->readings = $readings;
            $this->dispatch('refreshChartData', $this->readings);
        }

        if($avisos != $this->avisos){
            $this->avisos = $avisos;
        }


    }
}
