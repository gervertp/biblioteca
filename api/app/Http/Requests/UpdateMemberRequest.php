<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
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
            'name'      => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'phone'     => ['sometimes', 'nullable', 'string', 'max:20'],
            'email'     => ['sometimes', 'email', 'unique:members,email,' . $this->route('member')],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'   => 'El email no es válido.',
            'email.unique'  => 'Ya existe un socio con ese email.',
        ];
    }
}
