<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEspecialistaRequest extends FormRequest
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
        $especialista = $this->route('especialista');
        $especialistaId = $especialista?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('especialistas', 'nombre')->ignore($especialistaId)],
                'sede_id'   => ['required', 'integer', 'exists:sedes,id'],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('especialistas', 'nombre')->ignore($especialistaId)],
                'sede_id'   => ['sometimes', 'integer', 'exists:sede,id'],

            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('sedeId')) {
            $this->merge([
                'sede_id' => $this->sedeId,
            ]);
        }
    }
}
