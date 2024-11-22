<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use App\Services\AuvoService;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;

class ListaCliente extends Component
{
    use WireUiActions;

    public $loading = false;


    public function render()
    {

        $clientes = Cliente::paginate(10);

        return view('livewire.clientes.lista-cliente', compact('clientes'));
    }

    public function syncAuvo(){





        DB::table('clientes')->truncate();

        $auvoService = new AuvoService();

        $clients = $auvoService->getAllClients();


        foreach($clients as $client){

            DB::table('clientes')->insert([
                'cpfCnpj' => $client['cpfCnpj'],
                'externalId' => $client['externalId'],
                'phoneNumber' => json_encode($client['phoneNumber']),
                'email' => json_encode($client['email']),
                'manager' => $client['manager'],
                'note' => $client['note'],
                'address' => $client['address'],
                'latitude' => $client['latitude'],
                'longitude' => $client['longitude'],
                'maximumVisitTime' => $client['maximumVisitTime'],
                'unitMaximumTime' => $client['unitMaximumTime'],
                'groupsId' => json_encode($client['groupsId']),
                'managerTeamsId' => json_encode($client['managerTeamsId']),
                'managersId' => json_encode($client['managersId']),
                'uriAttachments' => json_encode($client['uriAttachments']),
                'segmentId' => $client['segmentId'],
                'active' => $client['active'],
                'adressComplement' => $client['adressComplement'],
                'dateLastUpdate' => $client['dateLastUpdate'],
                'creationDate' => $client['creationDate'],
                'contacts' => json_encode($client['contacts']),
                'auvoId' => $client['id'],
                'description' => $client['description'],
            ]);

        }



        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Sincronização realizada com sucesso!',
            'description' => 'Os clientes foram sincronizados com sucesso.',
        ]);



    }

}
