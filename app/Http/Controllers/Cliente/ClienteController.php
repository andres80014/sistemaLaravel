<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request){
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){
            $personas = Persona::orderBy('id','desc')->orderBy('id','desc')->paginate(3);
        }
        else{
            $personas = Persona::where($criterio, 'like', '%'. $buscar .'%')->orderBy('id','desc')->paginate(3);
        }
        return [
            'pagination'=>[
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function show($id){
        $persona = Persona::findOrFail($id);
        return $persona;
    }

    public function store(Request $request){
        $persona = New Persona();
        $persona->nombre         = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento  = $request->num_documento;
        $persona->direccion      = $request->direccion;
        $persona->telefono       = $request->telefono;
        $persona->email          = $request->email;

        $persona->save();
    }
    public function update(Request $request){
        $persona                 = Persona::findOrFail($request->id);
        $persona->nombre         = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento  = $request->num_documento;
        $persona->direccion      = $request->direccion;
        $persona->telefono       = $request->telefono;
        $persona->email          = $request->email;
        $persona->save();
    }
}
