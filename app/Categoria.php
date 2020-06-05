<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable=[
        'nombre',
        'descripcion',
        'condicion'
    ];

    public function articulos()
    {
        return $this->hasMany('App\Articulo','id','idcategoria');
    }
}
