<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'email'     => ['required', 'email', 'unique:members,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'El email no es válido.',
            'email.unique'       => 'Ya existe un socio con ese email.',
        ];
    }
}
