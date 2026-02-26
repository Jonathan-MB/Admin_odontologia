<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->rol_id === 1;
    }


    public function rules(): array
    {
        return [
            'nombre'        => ['required', 'string', 'max:45', 'unique:usuarios,nombre'],
            'correo'        => ['required', 'email', 'unique:usuarios,correo'],
            'contrasena'    => ['required', 'string', 'min:8'],
            'rol_id'        => ['required', 'integer', 'exists:rols,id'],
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'rol_id'        => $this->rolId,
            'contrasena'    => $this->password
        ]);
    }
}
