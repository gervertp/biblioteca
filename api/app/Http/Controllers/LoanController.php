<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Loan;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['member', 'book.autor'])->paginate(10);

        return LoanResource::collection($loans);
    }

    public function store(StoreLoanRequest $request)
    {
        $loan = Loan::create($request->validated());

        return new LoanResource($loan->load(['member', 'book.autor']));
    }

    public function show(string $id)
    {
        $loan = Loan::with(['member', 'book.autor'])->findOrFail($id);

        return new LoanResource($loan);
    }

    public function update(UpdateLoanRequest $request, string $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update($request->validated());

        return new LoanResource($loan->load(['member', 'book.autor']));
    }

    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Préstamo eliminado correctamente']);
    }
}
