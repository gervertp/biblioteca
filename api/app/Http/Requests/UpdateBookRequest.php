<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'author_id'      => ['sometimes', 'integer', 'exists:autors,id'],
            'title'          => ['sometimes', 'string', 'max:255'],
            'isbn'           => ['sometimes', 'string', 'max:20', 'unique:books,isbn,' . $this->route('book')],
            'genre'          => ['sometimes', 'string', 'max:100'],
            'published_year' => ['sometimes', 'integer', 'min:1000', 'max:2100'],
            'stock'          => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'author_id.exists'   => 'El autor seleccionado no existe.',
            'title.string'       => 'El título debe ser texto.',
            'isbn.unique'        => 'Ya existe un libro con ese ISBN.',
            'published_year.min' => 'El año de publicación no es válido.',
            'published_year.max' => 'El año de publicación no es válido.',
            'stock.min'          => 'El stock no puede ser negativo.',
        ];
    }
}
