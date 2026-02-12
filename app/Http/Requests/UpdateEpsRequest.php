<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEpsRequest extends FormRequest
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
        $eps = $this->route('eps');
        $epsId = $eps?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('eps', 'nombre')->ignore($epsId)],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('eps', 'nombre')->ignore($epsId)],
            ];
        }
    }
}
