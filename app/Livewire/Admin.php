<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AlertReading;
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
            $equipamentos[$code->equipment_code] = [
                'equipamento' => $equip,
                'cliente' => $customer
            ];
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
