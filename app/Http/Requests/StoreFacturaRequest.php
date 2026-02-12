<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacturaRequest extends FormRequest
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

            'cliente_id'      => ['required', 'integer', 'exists:clientes,id'],
            'especialista_id' => ['required', 'integer', 'exists:especialistas,id'],
            'abono'           => ['nullable', 'min:0', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'saldo'           => ['nullable', 'min:0', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],

        ];
    }

    protected  function prepareForValidation(): void
    {
        $this->merge([
            'cliente_id'      => $this->clienteId,
            'especialista_id' => $this->especialistaId,

        ]);
    }
}
