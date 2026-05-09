<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'isbn',
        'genre',
        'published_year',
        'stock',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'author_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
