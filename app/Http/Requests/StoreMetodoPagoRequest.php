<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMetodoPagoRequest extends FormRequest
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

            'nombre'        => ['required', 'string', 'max:45', 'unique:metodo_pagos,nombre'],

        ];
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
