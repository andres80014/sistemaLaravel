<?php

namespace App\Http\Controllers\Categoria;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Categoria;
use SebastianBergmann\CodeCoverage\TestFixture\C;

class CategoriaController extends Controller
{
    public function index(Request $request){
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){
            $categorias = Categoria::orderBy('id','desc')->paginate(3);
        }
        else{
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar .'%')->orderBy('id','desc')->paginate(3);
        }
        //if(!$request->ajax()) return redirect('/');
        //$categorias = Categoria::all();

        //$categorias = DB::table('categories')->paginate(3);


        //return $categorias;
        return [
            'pagination'=>[
                'total'        => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page'     => $categorias->perPage(),
                'last_page'    => $categorias->lastPage(),
                'from'         => $categorias->firstItem(),
                'to'           => $categorias->lastItem(),
            ],
            'categorias' => $categorias
        ];
    }

    public function show($id){
        $categoria              = Categoria::findOrFail($id);
        return $categoria;
    }
    public function store(Request $request){
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function update(Request $request) {
        $categoria              = Categoria::findOrFail($request->id);
        $categoria->nombre      = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion   = '1';
        $categoria->save();
    }

    public function desactivar(Request $request) {
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }

    public function activar(Request $request) {
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }
}
