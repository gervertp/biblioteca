<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Models\Autor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Autor::paginate(10);

        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $autor = Autor::create($request->validated());
        return new AuthorResource($autor);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $autor = Autor::findOrFail($id);
        return new AuthorResource($autor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, string $id)
    {
        $autor = Autor::findOrFail($id);
        $autor->update($request->validated());
        return new AuthorResource($autor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $authors = Autor::findOrFail($id);
        $authors->delete();
        return response()->json(['message' => 'Autor eliminado correctamente']);
    }
}
