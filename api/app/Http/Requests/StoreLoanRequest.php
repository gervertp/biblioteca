<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
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
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'book_id'   => ['required', 'integer', 'exists:books,id'],
            'loaned_at' => ['required', 'date'],
            'due_date'  => ['required', 'date', 'after:loaned_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'El socio es obligatorio.',
            'member_id.exists'   => 'El socio seleccionado no existe.',
            'book_id.required'  => 'El libro es obligatorio.',
            'book_id.exists'    => 'El libro seleccionado no existe.',
            'loaned_at.required' => 'La fecha de préstamo es obligatoria.',
            'loaned_at.date'     => 'La fecha de préstamo debe ser una fecha válida.',
            'due_date.required'  => 'La fecha de devolución es obligatoria.',
            'due_date.date'      => 'La fecha de devolución debe ser una fecha válida.',
            'due_date.after'     => 'La fecha de devolución debe ser posterior a la fecha de préstamo.',
        ];
    }
}
