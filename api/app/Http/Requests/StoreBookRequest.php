<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'author_id'      => ['required', 'integer', 'exists:autors,id'],
            'title'          => ['required', 'string', 'max:255'],
            'isbn'           => ['required', 'string', 'max:20', 'unique:books,isbn'],
            'genre'          => ['required', 'string', 'max:100'],
            'published_year' => ['required', 'integer', 'min:1000', 'max:2100'],
            'stock'          => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists'   => 'El autor seleccionado no existe.',
            'title.required'     => 'El título del libro es obligatorio.',
            'isbn.required'      => 'El ISBN es obligatorio.',
            'isbn.unique'        => 'Ya existe un libro con ese ISBN.',
            'genre.required'     => 'El género es obligatorio.',
            'published_year.required' => 'El año de publicación es obligatorio.',
            'published_year.min'      => 'El año de publicación no es válido.',
            'published_year.max'      => 'El año de publicación no es válido.',
            'stock.min'          => 'El stock no puede ser negativo.',
        ];
    }
}
