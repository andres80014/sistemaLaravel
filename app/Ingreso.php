<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table= 'ingresos';
    protected $primaryKey = 'id';
    protected $fillable = [
            'idproveedor',
            'idusuario',
            'tipo_comprobante',
            'serie_comprobante',
            'num_comprobante',
            'fecha_hora',
            'impuesto',
            'total',
            'estado'
        ];

    public function usuario()
    {
        return $this->belongsTo('App\User','idusuario','id');
    }

    public function proveedor(){
        return $this->belongsTo('App\Proveedor','idproveedor','id');
    }
}
