<?php

namespace App\Livewire\Equipamentos;

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

        $equipamentos = Equipamento::paginate(10);


        return view('livewire.equipamentos.equipamento-lista', compact('equipamentos'));
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
