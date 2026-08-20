<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMetodoPagoRequest extends FormRequest
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
        $metodoPago = $this->route('metodoPago');
        $metodoPagoId = $metodoPago?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('metodo_pagos', 'nombre')->ignore($metodoPagoId)],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('metodo_pagos', 'nombre')->ignore($metodoPagoId)],
            ];
        }
    }


    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique'   => 'Ya existe un metodo de pago con ese nombre.',
            'nombre.max'      => 'El nombre no puede pasar de 45 caracteres.',
        ];
    }
}
