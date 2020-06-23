<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index(Request $request){
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){
            $roles = Rol::orderBy('id','desc')->orderBy('id','desc')->paginate(3);
        }
        else{
            $roles = Rol::where($criterio, 'like', '%'. $buscar .'%')->orderBy('id','desc')->paginate(3);
        }
        return [
            'pagination'=>[
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),
            ],
            'roles' => $roles
        ];

        //return (CategoriaResource::collection($categorias));

    }

    public function selectRol()
    {
        $roles = Rol::where('condicion','=','1')
                ->select('id','nombre')
                ->orderBy('nombre','asc')
                ->get();
        return ['roles' => $roles];
    }
}
