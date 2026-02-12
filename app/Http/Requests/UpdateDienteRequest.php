<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDienteRequest extends FormRequest
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
        $diente = $this->route('diente');
        $dienteId = $diente?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('dientes', 'nombre')->ignore($dienteId)],
                'grupo_id'  => ['required', 'integer', 'exists:grupos,id'],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('dientes', 'nombre')->ignore($dienteId)],
                'grupo_id'  => ['sometimes', 'integer', 'exists:grupos,id'],

            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('grupoId')) {
            $this->merge([
                'grupo_id' => $this->grupoId,
            ]);
        }
    }
}
