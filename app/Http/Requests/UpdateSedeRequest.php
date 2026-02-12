<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSedeRequest extends FormRequest
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
        $sede = $this->route('sede');
        $sedeId = $sede?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('sedes', 'nombre')->ignore($sedeId)],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('sedes', 'nombre')->ignore($sedeId)],
            ];
        }
    }
}
