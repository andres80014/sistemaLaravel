<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable =[
        'idventa',
        'idarticulo',
        'cantidad',
        'precio',
        'descuento'
    ];

    public function articulo(){
        return $this->belongsTo('\App\Articulo','idarticulo','id');
    }


    public $timestamps = false;

}
