<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSedeRequest extends FormRequest
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

            'nombre'     => ['required', 'string', 'max:45', 'unique:sedes,nombre'],
            'no_factura' => ['nullable', 'integer', 'min:0'],
            'nit'        => ['required', 'string', 'max:45'],
            'direccion'  => ['required', 'string', 'max:120'],
            'telefono'   => ['required', 'string', 'max:45'],
            'celular'    => ['required', 'string', 'max:45'],

        ];
    }


    protected  function prepareForValidation(): void
    {
        $this->merge([

            'no_factura'  => $this->noFactura,

        ]);
    }
}
