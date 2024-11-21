<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ApiController extends Controller
{
    //

    public function clientes(Request $request){

        return Cliente::query()
            ->select('auvoId', 'description')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('description', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('auvoId', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->orderBy('description')
            ->get();
    }


}
