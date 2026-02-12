<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacturaRequest extends FormRequest
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

        if ($this->isMethod('put')) {
            return [
                'cliente_id'      => ['required', 'integer', 'exists:clientes,id'],
                'especialista_id' => ['required', 'integer', 'exists:especialistas,id'],
                'abono'           => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
                'saldo'           => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            ];
        }


        //PATCH 
        return [

            'cliente_id'      => ['sometimes', 'integer', 'exists:clientes,id'],
            'especialista_id' => ['sometimes', 'integer', 'exists:especialistas,id'],
            'abono'           => ['sometimes', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'saldo'           => ['sometimes', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('clienteId')) {
            $data['cliente_id'] = $this->clienteId;
        }

        if ($this->has('especialistaId')) {
            $data['especialista_id'] = $this->especialistaId;
        }

        $this->merge($data);
    }
}
