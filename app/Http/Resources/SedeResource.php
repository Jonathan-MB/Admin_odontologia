<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SedeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'nombre'        => $this->nombre,
            'no_factura'    => $this->no_factura,
            'nit'           => $this->nit,
            'direccion'     => $this->direccion,
            'telefono'      => $this->telefono,
            'celular'       => $this->celular,



            'especialistas' => EspecialistaResource::collection($this->whenLoaded('especialistas')),
        ];
    }
}
