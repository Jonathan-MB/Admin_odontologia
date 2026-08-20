<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCitaRequest extends FormRequest
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
            'fecha_hora'      => ['required', 'date'],

        ];
    }


    public function messages(): array
    {
        return [
            'cliente_id.required'      => 'Debes elegir un paciente.',
            'especialista_id.required' => 'Debes elegir un doctor.',
            'fecha_hora.required'      => 'Debes indicar fecha y hora.',
        ];
    }
}
