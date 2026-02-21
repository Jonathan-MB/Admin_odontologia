<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacturaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'noFactura'         => $this->no_factura,
            'clienteId'         => $this->cliente_id,
            'especialistaId'    => $this->especialista_id,
            'fecha'             => Carbon::parse($this->created_at)
                ->setTimezone('America/Bogota')
                ->format('H:i:s d-m-Y'),
            'nombre'             => $this->nombre,
            'abono'             => $this->abono,
            'saldo'             => $this->saldo,
        ];
    }
}
