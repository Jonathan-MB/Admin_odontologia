<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
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
        $cliente = $this->route('cliente');
        $clienteId = $cliente?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'            => ['required', 'string', 'max:45'],
                'primer_apellido'   => ['required', 'string', 'max:45'],
                'segundo_apellido'  => ['required', 'string', 'max:45'],
                'numero_documento'  => ['required', 'string', 'max:45', Rule::unique('clientes', 'numero_documento')->ignore($clienteId)],
                'correo'            => ['nullable', 'email', 'max:150'],
                'telefono'          => ['required', 'string', 'max:45'],
                'direccion'         => ['required', 'string', 'max:80'],
                'fecha_nacimiento'  => ['required', 'date', 'before:today'],
                'fecha_cita'        => ['nullable', 'date', 'after_or_equal:today'],
                'saldo'             => ['nullable', 'min:0', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
                'tipo_documento_id' => ['required', 'integer', 'exists:tipo_documentos,id'],
                'eps_id'            => ['required', 'integer', 'exists:eps,id'],
                'sede_id'           => ['nullable', 'integer', 'exists:sedes,id'],

            ];
        }




        //PATCH 
        return [
            'nombre'            => ['sometimes', 'string', 'max:45'],
            'primer_apellido'   => ['sometimes', 'string', 'max:45'],
            'segundo_apellido'  => ['sometimes', 'string', 'max:45'],
            'numero_documento'  => ['sometimes', 'string', 'max:45', Rule::unique('clientes', 'numero_documento')->ignore($clienteId)],
            'correo'            => ['sometimes', 'email', 'max:150'],
            'telefono'          => ['sometimes', 'string', 'max:45'],
            'direccion'         => ['sometimes', 'string', 'max:80'],
            'fecha_nacimiento'  => ['sometimes', 'date', 'before:today'],
            'fecha_cita'        => ['sometimes', 'nullable','date', 'after_or_equal:today'],
            'saldo'             => ['sometimes', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'tipo_documento_id' => ['sometimes', 'integer', 'exists:tipo_documentos,id'],
            'eps_id'            => ['sometimes', 'integer', 'exists:eps,id'],
            'sede_id'           => ['sometimes', 'integer', 'exists:sedes,id'],

        ];
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('primerApellido')) {
            $data['primer_apellido'] = $this->primerApellido;
        }

        if ($this->has('segundoApellido')) {
            $data['segundo_apellido'] = $this->segundoApellido;
        }

        if ($this->has('numeroDocumento')) {
            $data['numero_documento'] = $this->numeroDocumento;
        }

        if ($this->has('fechaNacimiento')) {
            $data['fecha_nacimiento'] = $this->fechaNacimiento;
        }

        if ($this->has('fechaCita')) {
            $data['fecha_cita'] = $this->fechaCita;
        }

        if ($this->has('tipoDocumentoId')) {
            $data['tipo_documento_id'] = $this->tipoDocumentoId;
        }

        if ($this->has('epsId')) {
            $data['eps_id'] = $this->epsId;
        }
        if ($this->has('sedeId')) {
            $data['sede_id'] = $this->sedeId;
        }

        $this->merge($data);
    }
}
