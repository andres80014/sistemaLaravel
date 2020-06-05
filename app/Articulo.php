<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categoria;
class Articulo extends Model
{
    protected $table = 'articulos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idcategoria',
        'codigo',
        'nombre',
        'precio_venta',
        'stock',
        'descripcion',
        'condicion'
    ];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
}
