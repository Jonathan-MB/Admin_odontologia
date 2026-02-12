<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHistoriaRequest extends FormRequest
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
                'diente_id'       => ['required', 'integer', 'exists:dientes,id'],
                'observacion'     => ['required', 'string', 'max:900'],
            ];
        }


        //PATCH 
        return [

            'cliente_id'      => ['sometimes', 'integer', 'exists:clientes,id'],
            'especialista_id' => ['sometimes', 'integer', 'exists:especialistas,id'],
            'diente_id'       => ['sometimes', 'integer', 'exists:dientes,id'],
            'observacion'     => ['sometimes', 'string', 'max:900'],
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

        if ($this->has('dienteId')) {
            $data['diente_id'] = $this->dienteId;
        }

        $this->merge($data);
    }
}
