<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false; // NO Permitir actualiar rol
    }

    public function rules(): array
    {
        $rol = $this->route('rol');
        $rolId = $rol?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('rols', 'nombre')->ignore($rolId)],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('rols', 'nombre')->ignore($rolId)],
            ];
        }
    }
}
