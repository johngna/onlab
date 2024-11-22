<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Mail\SendPassword;
use App\Services\AuvoService;
use Illuminate\Support\Facades\Mail;

class Usuarios extends Component
{

    public $usuario = [];

    public function render()
    {

        $usuarios = User::paginate();

        return view('livewire.usuarios', compact('usuarios'));
    }

    public function getClientAuvo(){

        $auvoService = new AuvoService();
        $usuario = $auvoService->getClient($this->usuario['customer_id']);

        $this->usuario['customer_name'] = $usuario['description'];

    }

    public function save(){


        if(isset($this->usuario['id'])){

            $this->validate([
                'usuario.name' => 'required',
                'usuario.email' => 'required|email',
            ]);

            User::find($this->usuario['id'])->update($this->usuario);

            return redirect()->route('usuarios');

        }else{


            $this->validate([
                'usuario.name' => 'required',
                'usuario.email' => 'required|email',
            ]);

            //gerar senha aleatória temporária letras e numeros de 8 digitos
            $senha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 8 );

            User::create([
                'name' => $this->usuario['name'],
                'email' => $this->usuario['email'],
                'password' => bcrypt($senha),
                'customer_id' => $this->usuario['customer_id'],
                'customer_name' => $this->usuario['customer_name'],
                'role_id' => $this->usuario['role_id'],
            ]);

            //enviar email com a senha
            Mail::to($this->usuario['email'])->send(new SendPassword($senha, $this->usuario['email']));

            return redirect()->route('usuarios');

        }


    }


    public function edit($id){

        $this->usuario = User::find($id)->toArray();

    }

}
