<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'birth_date'  => ['nullable', 'date'],
            'bio'         => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del autor es obligatorio.',
            'name.string'   => 'El nombre debe ser texto.',
            'name.max'      => 'El nombre no puede tener más de 255 caracteres.',
            'nationality.string' => 'La nacionalidad debe ser texto.',
            'nationality.max'    => 'La nacionalidad no puede tener más de 255 caracteres.',
            'birth_date.date'    => 'La fecha de nacimiento debe ser una fecha válida. Ejemplo: 1927-03-06.',
            'bio.string'         => 'La biografía debe ser texto.',
        ];
    }
}
