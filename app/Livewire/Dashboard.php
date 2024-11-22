<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Equipamento;
use App\Services\AuvoService;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{

    public $tasks = [];
    public $equipamentos = [];
    public $data_proxima_visita;

    public function render()
    {

        $this->getData();

        //data proxima visita 6 meses após o ultimo atendimento
        $lastTaskIndex = count($this->tasks) - 1;
        $lastTaskDate = $this->tasks[$lastTaskIndex]['taskDate'];
        $this->data_proxima_visita = date('Y-m-d', strtotime($lastTaskDate . ' +6 months'));



        return view('livewire.dashboard');
    }

    public function getData(){

        $auvoService = new AuvoService();

        $tasks = $auvoService->getTasks(Auth::user()->customer_id);
        $equipamentos = $auvoService->getEquipments(Auth::user()->customer_id);

        // dd($equipamentos);

        $this->tasks = $tasks;
        $this->equipamentos = $equipamentos;

    }



}
