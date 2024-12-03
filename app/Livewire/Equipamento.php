<?php

namespace App\Livewire;

use App\Models\AlertReading;
use App\Models\DataReadings;
use App\Models\LevelReading;
use App\Models\ReservReading;
use Livewire\Component;
use App\Models\SensorReading;
use App\Models\TimeReading;
use App\Services\AuvoService;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Time;

class Equipamento extends Component
{


    public $readings;
    public $equipamento;
    public $cliente;
    public $integra = false;
    public $avisos = [];
    public $reservs = [];
    public $levels = [];
    public $times = [];
    public $status = 'online';
    public $ultima_atualizacao;


    public function mount(Request $request)
    {

        $auvoService = new AuvoService();
        $this->equipamento = $auvoService->getEquipament(request()->route('numero_serie'));

        $this->cliente = $auvoService->getClient($this->equipamento['associatedCustomerId']);

        //$this->readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();
         $this->readings = SensorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();

        //$this->avisos = AlertReading::orderBy('created_at', 'desc')->first();
         $this->avisos = AlertReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();

        $this->reservs = ReservReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();

        $this->levels = LevelReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();

        $this->times = TimeReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();

        $last_reading = DataReadings::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();

            if($last_reading){
                $last_reading_time = strtotime($last_reading->created_at);
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $this->status = ($current_time - $last_reading_time) > 60 ? 'offline' : 'online';
                $this->ultima_atualizacao = date('d/m/Y H:i:s', strtotime($last_reading->created_at));
            }

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
        // $readings = SensorReading::orderBy('created_at', 'desc')->take(12)->get();
        $readings = SensorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();

        // $avisos = AlertReading::orderBy('created_at', 'desc')->first();
        $avisos = AlertReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();


        $this->reservs = ReservReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();


        //verifica se houve alteração nos dados
        if($readings != $this->readings){
            $this->readings = $readings;
            $this->dispatch('refreshChartData', $this->readings);
        }else{

            $last_reading = DataReadings::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();

            if($last_reading){
                $last_reading_time = strtotime($last_reading->created_at);
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $this->status = ($current_time - $last_reading_time) > 60 ? 'offline' : 'online';
            }


        }

        if($avisos != $this->avisos){
            $this->avisos = $avisos;
        }


    }
}
