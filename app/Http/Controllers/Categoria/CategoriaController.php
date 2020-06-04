<?php

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Categoria;
use SebastianBergmann\CodeCoverage\TestFixture\C;

class CategoriaController extends Controller
{
    public function index(){
        $categorias = Categoria::all();
        return $categorias;
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
