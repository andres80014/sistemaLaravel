<?php

namespace App\Http\Controllers\Articulo;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticuloResource;
use Illuminate\Http\Request;
use App\Articulo;
class ArticuloController extends Controller
{
    public function index(Request $request){
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){
            $articulos = Articulo::join('categories','articulos.idcategoria', '=','categories.id')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categories.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                ->orderBy('articulos.id','desc')->paginate(3);
        }
        else{
            $articulos = Articulo::join('categories','articulos.idcategoria', '=','categories.id')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categories.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                ->where('articulos.'.$criterio,'like','%'.$buscar.'%')
                ->orderBy('articulos.id','desc')->paginate(3);
        }
        return [
            'pagination'=>[
                'total'        => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page'     => $articulos->perPage(),
                'last_page'    => $articulos->lastPage(),
                'from'         => $articulos->firstItem(),
                'to'           => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }
    public function store(Request $request){
        $articulo = new Articulo();
        $articulo->idcategoria  = $request->idcategoria;
        $articulo->codigo       = $request->codigo;
        $articulo->nombre       = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock        = $request->stock;
        $articulo->descripcion  = $request->descripcion;
        $articulo->condicion    = '1';
        $articulo->save();
    }

    public function update(Request $request){
        $articulo              = Articulo::findOrFail($request->id);
        $articulo->idcategoria  = $request->idcategoria;
        $articulo->codigo       = $request->codigo;
        $articulo->nombre       = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock        = $request->stock;
        $articulo->descripcion  = $request->descripcion;
        $articulo->condicion    = '1';
        $articulo->save();
    }
    public function show($id)
    {
        $articulo = Articulo::findOrFail($id);

        return new ArticuloResource($articulo);
    }
    public function activar(Request $request){
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }
    public function desactivar(Request $request){
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }
}