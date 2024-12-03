<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AlertReading;
use App\Models\DataReadings;
use App\Services\AuvoService;

class Admin extends Component
{



    public function render()
    {

        $equipamentos = [];

        $codes = AlertReading::groupBy('equipment_code')->get('equipment_code');

        foreach ($codes as $code) {
            $equip = $this->getEquipament($code->equipment_code);
            $customer = $this->getClient($equip['associatedCustomerId']);
            $last_reading = DataReadings::where('equipment_code', $code->equipment_code)->orderBy('created_at', 'desc')->first();

            $status = 'offline';
            $ultima_atualizacao = '';

            if($last_reading){
                $last_reading_time = strtotime($last_reading->created_at);
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $status = ($current_time - $last_reading_time) > 60 ? 'offline' : 'online';
                $ultima_atualizacao = date('d/m/Y H:i:s', strtotime($last_reading->created_at));
            }

            $equipamentos[$code->equipment_code] = [
                'equipamento' => $equip,
                'cliente' => $customer,
                'status' => $status,
                'ultima_atualizacao' => $ultima_atualizacao
            ];
        }

        if($last_reading){
            $last_reading_time = strtotime($last_reading->created_at);
            $current_time = strtotime(date('Y-m-d H:i:s'));
            $this->status = ($current_time - $last_reading_time) > 60 ? 'offline' : 'online';
            $this->ultima_atualizacao = date('d/m/Y H:i:s', strtotime($last_reading->created_at));
        }


        return view('livewire.admin', compact('equipamentos'));
    }

    public function getEquipament($code)
    {
        $auvoService = new AuvoService();
        return $auvoService->getEquipmentData($code)['result']['entityList'][0];
    }

    public function getClient($id)
    {
        $auvoService = new AuvoService();
        return $auvoService->getClient($id);
    }
}
