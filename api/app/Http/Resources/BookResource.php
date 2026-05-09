<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'isbn'           => $this->isbn,
            'genre'          => $this->genre,
            'published_year' => $this->published_year,
            'stock'          => $this->stock,
            'autor'          => new AuthorResource($this->whenLoaded('autor')),
        ];
    }
}
