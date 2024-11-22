<?php

namespace App\Livewire\Equipamentos;

use App\Models\AlertReading;
use App\Models\Equipamento;
use Livewire\Component;
use App\Services\AuvoService;
use WireUi\Traits\WireUiActions;

class EquipamentoLista extends Component
{

    use WireUiActions;

    protected $auvoService;
    public $equipamento = [];


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

        // dd($equipamentos);

        return view('livewire.equipamentos.equipamento-lista', compact('equipamentos'));
    }


    public function getEquipament($code)
    {
        $this->auvoService = new AuvoService();
        return $this->auvoService->getEquipmentData($code)['result']['entityList'][0];
    }

    public function getClient($id)
    {
        $this->auvoService = new AuvoService();
        return $this->auvoService->getClient($id);
    }


    public function getEquipAuvo()
    {

        if (empty($this->equipamento['numero_serie'])) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Equipamento não localizado!',
                'description' => 'Não foi possível localizar o equipamento com o número de série informado.',
            ]);
        }else{

            $this->auvoService = new AuvoService();
            $response = $this->auvoService->getEquipmentData($this->equipamento['numero_serie']);


                if($this->equipamento['numero_serie'] == $response['result']['entityList'][0]['identifier'])
                {
                    $this->equipamento['nome'] = $response['result']['entityList'][0]['name'];
                    $this->equipamento['descricao'] = $response['result']['entityList'][0]['description'];
                    $this->equipamento['modelo'] = $response['result']['entityList'][0]['categoryId'];
                    $this->equipamento['cliente'] = $response['result']['entityList'][0]['associatedCustomerId'];
                }else{

                    $this->dialog()->show([
                        'icon' => 'error',
                        'title' => 'Equipamento não localizado!',
                        'description' => 'Não foi possível localizar o equipamento com o número de série informado.',
                    ]);

                }

        }



    }


    public function save(){

        Equipamento::create($this->equipamento);

        $this->equipamento = [];

        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Equipamento cadastrado!',
            'description' => 'O equipamento foi cadastrado com sucesso.',
        ]);


    }


}
