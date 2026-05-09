<?php

namespace App\Http\Resources;

use App\Http\Resources\BookResource;
use App\Http\Resources\MemberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'loaned_at'   => $this->loaned_at,
            'due_date'    => $this->due_date,
            'returned_at' => $this->returned_at,
            'member' => new MemberResource($this->whenLoaded('member')),
            'book'        => new BookResource($this->whenLoaded('book')),
        ];
    }
}
