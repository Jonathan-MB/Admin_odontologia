<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDienteRequest extends FormRequest
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

            'nombre'    => ['required', 'string', 'max:45', 'unique:dientes,nombre'],
            'grupo_id'  => ['required', 'integer', 'exists:grupos,id'],

        ];
    }

    protected  function prepareForValidation(): void
    {
        $this->merge([
            'grupo_id' => $this->grupoId,
        ]);
    }
}
