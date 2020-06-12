<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre','tipo_documento','num_documento','direccion','telefono','email'];
}
