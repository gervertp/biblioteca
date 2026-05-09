<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('autor')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%")
                    ->orWhereHas('autor', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10);

        return BookResource::collection($books);
    }

    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return new BookResource($book->load('autor'));
    }

    public function show(string $id)
    {
        $book = Book::with('autor')->findOrFail($id);

        return new BookResource($book);
    }

    public function update(UpdateBookRequest $request, string $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->validated());

        return new BookResource($book->load('autor'));
    }

    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Libro eliminado correctamente']);
    }
}
