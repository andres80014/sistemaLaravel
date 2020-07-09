<?php

namespace App\Http\Controllers\Ingreso;

use App\DetalleIngreso;
use App\Http\Controllers\Controller;
use App\Ingreso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
                ->join('users','ingresos.idusuario','=','users.id')
                ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
                    'ingresos.total','ingresos.estado','personas.nombre as proveedor','users.usuario')
                ->orderBy('ingresos.id', 'desc')->paginate(3);
        } else {
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
                ->join('users','ingresos.idusuario','=','users.id')
                ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
                    'ingresos.total','ingresos.estado','personas.nombre as proveedor','users.usuario')
                ->where('ingresos.'.$criterio, 'like', '%' . $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total' => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page' => $ingresos->perPage(),
                'last_page' => $ingresos->lastPage(),
                'from' => $ingresos->firstItem(),
                'to' => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }
    public function obtenerCabecera(Request $request){
        $id = $request->id;

        $ingreso = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
                'ingresos.total','ingresos.estado','personas.nombre as proveedor','users.usuario')
            ->where('ingresos.id','=',$id)->take(1)->get();
        return ['ingreso' => $ingreso];

    }

    public function obtenerDetalles(Request $request){
        $id = $request->id;

        $detalles = DetalleIngreso::join('articulos','detalle_ingresos.idarticulo','=','articulos.id')
            ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','articulos.nombre as articulo')
            ->where('detalle_ingresos.idingreso','=',$id)
            ->orderBy('detalle_ingresos.id','desc')->get();
        return ['detalles' => $detalles];
    }
    public function show($id)
    {
        $usuario = Ingreso::findOrFail($id);
        return $usuario;
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $mytime = Carbon::now('America/Bogota');

            $ingreso = New Ingreso();
            $ingreso->idproveedor       = $request->idproveedor;
            $ingreso->idusuario         = Auth::user()->id;
            $ingreso->tipo_comprobante  = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante   = $request->num_comprobante;
            $ingreso->fecha_hora        = $mytime->toDateString();
            $ingreso->impuesto          = $request->impuesto;
            $ingreso->total             = $request->total;
            $ingreso->estado            = 'Registrado';
            $ingreso->save();

            $detalles = $request->data;

            foreach ($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso  = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad   = $det['cantidad'];
                $detalle->precio     = $det['precio'];
                $detalle->save();
            }
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request){
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
}
