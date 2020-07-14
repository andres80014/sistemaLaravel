<?php

namespace App\Http\Controllers\Venta;

use App\DetalleVenta;
use App\Http\Controllers\Controller;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto',
                    'ventas.total','ventas.estado','personas.nombre as cliente','users.usuario')
                ->orderBy('ventas.id', 'desc')->paginate(3);
        } else {
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante',
                    'ventas.fecha_hora','ventas.impuesto',
                    'ventas.total','ventas.estado','personas.nombre as cliente','users.usuario')
                ->where('ventas.'.$criterio, 'like', '%' . $buscar . '%')->orderBy('ventas.id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }

    public function obtenerCabecera(Request $request){
        $id = $request->id;

        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante','ventas.num_comprobante',
                'ventas.fecha_hora','ventas.impuesto',
                'ventas.total','ventas.estado','personas.nombre as cliente','users.usuario')
            ->where('ventas.id','=',$id)->take(1)->get();
        return ['venta' => $venta];

    }

    public function obtenerDetalles(Request $request){
        $id = $request->id;

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.nombre as articulo')
            ->where('detalle_ventas.idventa','=',$id)
            ->orderBy('detalle_ventas.id','desc')->get();
        return ['detalles' => $detalles];
    }
    public function show($id)
    {
        $venta = Venta::findOrFail($id);
        return $venta;
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $mytime = Carbon::now('America/Bogota');

            $venta = New Venta();
            $venta->idcliente         = $request->idcliente;
            $venta->idusuario         = Auth::user()->id;
            $venta->tipo_comprobante  = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante   = $request->num_comprobante;
            $venta->fecha_hora        = $mytime->toDateString();
            $venta->impuesto          = $request->impuesto;
            $venta->total             = $request->total;
            $venta->estado            = 'Registrado';
            $venta->save();

            $detalles = $request->data;

            foreach ($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa  = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad   = $det['cantidad'];
                $detalle->precio     = $det['precio'];
                $detalle->descuento  = $det['descuento'];

                $detalle->save();
            }
            DB::commit();
            return ['id'=>$venta->id];
        }
        catch(\Exception $exception){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request){
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }
    public function pdf(Request $request,$id){
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
                'ventas.num_comprobante','ventas.created_at','ventas.impuesto',
                'ventas.total','ventas.estado','personas.nombre as cliente','personas.tipo_documento',
                'personas.num_documento',
                'personas.direccion','personas.email','personas.telefono','users.usuario')
            ->where('ventas.id','=',$id)->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                     'articulos.nombre as articulo')
            ->where('detalle_ventas.idventa','=',$id)
            ->orderBy('detalle_ventas.id','desc')->get();

        $numVenta = Venta::select('num_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('venta-'.$numVenta[0]->num_comprobante.'.pdf');
    }
}
