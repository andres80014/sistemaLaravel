<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected  $table = 'ventas';
    protected  $primaryKey = 'id';
    protected  $fillable = [
        'idcliente',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];

    public function cliente(){
        return $this->belongsTo('\App\Personas','idcliente','id');
    }

    public function usuario(){
        return $this->belongsTo('App\Users','idusuario','id');
    }



}
