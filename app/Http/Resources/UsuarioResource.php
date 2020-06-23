<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
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
            'id'             => $this->id,
            'nombre'         => $this->persona->nombre,
            'tipo_documento' => $this->persona->tipo_documento,
            'num_documento'  => $this->persona->num_documento,
            'direccion'      => $this->persona->direccion,
            'telefono'       => $this->persona->telefono,
            'email'          => $this->persona->email,
            'usuario'        => $this->usuario,
            'condicion'      => $this->condicion,
            'rol'            => $this->rol->nombre
        ];
    }
}
