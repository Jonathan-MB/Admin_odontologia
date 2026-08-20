<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
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

            'nombre'            => ['required', 'string', 'max:45'],
            'primer_apellido'   => ['required', 'string', 'max:45'],
            'segundo_apellido'  => ['nullable', 'string', 'max:45'],
            'numero_documento'  => ['required', 'string', 'max:45', 'unique:clientes,numero_documento'],
            'correo'            => ['nullable', 'email', 'max:150'],
            'telefono'          => ['required', 'string', 'max:45'],
            'direccion'         => ['required', 'string', 'max:80'],
            'fecha_nacimiento'  => ['required', 'date', 'before:today'],
            'fecha_cita'        => ['nullable', 'date', 'after_or_equal:today'],
            'saldo'             => ['nullable', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'tipo_documento_id' => ['required', 'integer', 'exists:tipo_documentos,id'],
            'eps_id'            => ['required', 'integer', 'exists:eps,id'],
            'sede_id'           => ['nullable', 'integer', 'exists:sedes,id'],

        ];
    }

    protected  function prepareForValidation(): void
    {
        $this->merge([
            'primer_apellido'   => $this->primerApellido,
            'segundo_apellido'  => $this->segundoApellido  ?? null,
            'numero_documento'  => $this->numeroDocumento,
            'fecha_nacimiento'  => $this->fechaNacimiento,
            'fecha_cita'        => $this->fechaCita ?? null,
            'tipo_documento_id' => $this->tipoDocumentoId,
            'eps_id'            => $this->epsId,
            'sede_id'           => $this->sedeId ?? null,
            'telefono'          => $this->telefono,
            'correo'            => $this->correo ?? null,
        ]);
    }
}
