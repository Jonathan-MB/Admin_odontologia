<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEspecialistaRequest extends FormRequest
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

            'nombre'    => ['required', 'string', 'max:45', 'unique:especialistas,nombre'],
            'sede_id'   => ['required', 'integer', 'exists:sede,id'],

        ];
    }

    protected  function prepareForValidation(): void
    {
        $this->merge([
            'sede_id' => $this->sedeId,
        ]);
    }
}
