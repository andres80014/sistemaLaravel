<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $personas = User::join('personas','users.id','=','personas.id')
                ->join('roles','users.idrol','=','roles.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion',
                    'personas.telefono','personas.email','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol')
                ->orderBy('personas.id', 'desc')->paginate(3);
        } else {
            $personas = User::join('personas','users.id','=','personas.id')
                ->join('roles','users.idrol','=','roles.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion',
                    'personas.telefono','personas.email','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol')
                ->where('personas.'.$criterio, 'like', '%' . $buscar . '%')->orderBy('personas.id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total' => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function show($id)
    {
        $usuario = User::findOrFail($id);
        return $usuario;
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $persona = New Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $usuario            = New User();
            $usuario->id        = $persona->id;
            $usuario->usuario   = $request->usuario;
            $usuario->password  = bcrypt($request->password);
            $usuario->condicion = 1;
            $usuario->idrol       = $request->idrol;
            $usuario->save();
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        try{
            DB::beginTransaction();
            $usuario = User::findOrFail($request->id);

            $persona = Persona::findOrFail($usuario->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $usuario->id = $persona->id;
            $usuario->usuario = $request->usuario;
            $usuario->password = bcrypt($request->password);
            $usuario->condicion = 1;
            $usuario->idrol      = $request->idrol;
            $usuario->save();

            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
        }
    }

    public function activar(Request $request){
        $usuario = User::findOrFail($request->id);
        $usuario->condicion = '1';
        $usuario->save();
    }
    public function desactivar(Request $request){
        $usuario = User::findOrFail($request->id);
        $usuario->condicion = '0';
        $usuario->save();
    }
}
