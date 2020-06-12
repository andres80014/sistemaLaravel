<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticuloResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'idcategoria' => $this->idcategoria,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'nombre_categoria' => $this->categoria->nombre,
            'precio_venta' => $this->precio_venta,
            'stock' => $this->stock,
            'descripcion' => $this->descripcion,
            'condicion' => $this->condicion
        ];
    }
}
