<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoDocumentoRequest extends FormRequest
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
        $tipoDocumento = $this->route('tipoDocumento');
        $tipoDocumentoId = $tipoDocumento?->id;

        if ($this->isMethod('put')) {
            return [
                'nombre'    => ['required', 'string', 'max:45', Rule::unique('tipo_documentos', 'nombre')->ignore($tipoDocumentoId)],
            ];
        } else {
            return [

                'nombre'    => ['sometimes', 'string', 'max:45',  Rule::unique('tipo_documentos', 'nombre')->ignore($tipoDocumentoId)],
            ];
        }
    }
}
