<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHistoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [

            'cliente_id'        => ['required', 'integer', 'exists:clientes,id'],
            'especialista_id'   => ['required', 'integer', 'exists:especialistas,id'],
            'diente_id'         => ['required', 'integer', 'exists:dientes,id'],
            'fecha'             => ['required', 'date',],
            'observacion'       => ['required', 'string','max:900'],

        ];
    }

    protected  function prepareForValidation(): void
    {
        $this->merge([
            'cliente_id'      => $this->clienteId,
            'especialista_id' => $this->especialistaId,
            'diente_id' => $this->dienteId,

        ]);
    }
}