<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TimeReading;
use App\Models\AlertReading;
use App\Models\DataReadings;
use App\Models\LevelReading;
use Illuminate\Http\Request;
use App\Models\ReservReading;
use App\Models\SensorReading;
use App\Services\AuvoService;
use App\Models\ActuatorReading;
use App\Models\BombReading;
use App\Models\ValveReading;

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
    public $actuators = [];
    public $bombs = [];
    public $valves = [];
    public $status = 'online';
    public $ultima_atualizacao;
    public $abas = ['Indicadores', 'Especificações'];
    public $aba_atual = 'Indicadores';
    public $classe = ['ativa' => 'inline-block w-full p-4 text-gray-900 bg-blue-100 border-r border-gray-200 dark:border-gray-700 rounded-s-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white',
        'inativa' => 'inline-block w-full p-4 bg-white border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700'];



    public function mount(Request $request)
    {

        $auvoService = new AuvoService();
        $this->equipamento = $auvoService->getEquipament(request()->route('numero_serie'));

        $this->cliente = $auvoService->getClient($this->equipamento['associatedCustomerId']);
        $this->readings = SensorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();
        $this->avisos = AlertReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();
        $this->reservs = ReservReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();
        $this->levels = LevelReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();
        $this->times = TimeReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();
        $this->actuators = ActuatorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();
        $this->bombs = BombReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();
        $this->valves = ValveReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->first();

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

    public function atualizaAba($aba)
    {
        $this->aba_atual = $aba;
        $this->dispatch('InitializeChartData', $this->readings);
    }

    public function fetchEquipmentData()
    {

        if($this->aba_atual == 'Indicadores'){


            $readings = SensorReading::where('equipment_code', $this->equipamento['identifier'])->orderBy('created_at', 'desc')->take(12)->get();
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
}
