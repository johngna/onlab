<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $guarded = [];



    public function customer()
    {
        return $this->belongsTo(Cliente::class, 'cliente', 'auvoId');
    }

}
