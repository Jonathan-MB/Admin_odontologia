<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
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
        $usuario = $this->route('usuario');
        $usuarioId = $usuario?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'        => ['required', 'string', 'max:45', Rule::unique('usuarios', 'nombre')->ignore($usuarioId)],
                'correo'        => ['required', 'email', Rule::unique('usuarios', 'correo')->ignore($usuarioId)],
                'contrasena'    => ['sometimes', 'string', 'min:8'],
                'rol_id'        => ['required', 'integer', 'exists:rols,id'],

            ];
        }




        //PATCH 
        return [
            'nombre'        => ['sometimes', 'string', 'max:45', Rule::unique('usuarios', 'nombre')->ignore($usuarioId)],
            'correo'        => ['sometimes', 'email', Rule::unique('usuarios', 'correo')->ignore($usuarioId)],
            'contrasena'    => ['sometimes', 'string', 'min:8'],
            'rol_id'        => ['sometimes', 'integer', 'exists:rols,id'],

        ];
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('rolId')) {
            $data['rol_id'] = $this->rolId;
        }

        if ($this->filled('password')) { 
            $data['contrasena'] = bcrypt($this->password);
        }

        $this->merge($data);
    }
}
