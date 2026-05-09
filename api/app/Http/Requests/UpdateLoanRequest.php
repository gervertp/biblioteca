<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLoanRequest extends FormRequest
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
            'returned_at' => ['sometimes', 'nullable', 'date'],
            'due_date'    => ['sometimes', 'date', 'after:loaned_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'returned_at.date' => 'La fecha de devolución debe ser una fecha válida.',
            'due_date.date'    => 'La fecha límite debe ser una fecha válida.',
            'due_date.after'   => 'La fecha límite debe ser posterior a la fecha de préstamo.',
        ];
    }
}
