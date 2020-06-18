<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use App\Persona;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion',
                         'personas.telefono','personas.email','proveedores.contacto','proveedores.telefono_contacto')
                ->orderBy('personas.id', 'desc')->paginate(3);
        } else {
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion',
                    'personas.telefono','personas.email','proveedores.contacto','proveedores.telefono_contacto')
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
        $provedor = Proveedor::findOrFail($id);
        return $provedor;
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

            $proveedor = New Proveedor();
            $proveedor->id = $persona->id;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->numero_contacto;
            $proveedor->save();
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
            $proveedor = Proveedor::findOrFail($request->id);
            $persona = Persona::findOrFail($proveedor->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->numero_contacto;
            $proveedor->save();

            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
        }
    }
}
