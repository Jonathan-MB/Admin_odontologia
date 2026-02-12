<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreHistoriaRequest extends FormRequest
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
            'historias' => ['required', 'array', 'min:1'],

            'historias.*.cliente_id'      => ['required', 'integer', 'exists:clientes,id'],
            'historias.*.especialista_id' => ['required', 'integer', 'exists:especialistas,id'],
            'historias.*.diente_id'       => ['required', 'integer', 'exists:dientes,id'],
            'historias.*.observacion'     => ['required', 'string', 'max:900'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'historias' => collect($this->historias)->map(fn ($h) => [
                'cliente_id'      => $h['clienteId'] ?? null,
                'especialista_id' => $h['especialistaId'] ?? null,
                'diente_id'       => $h['dienteId'] ?? null,
                'observacion'     => $h['observacion'] ?? null,
            ])->toArray()
        ]);
    }
}
