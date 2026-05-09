<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })->paginate(15);

        return MemberResource::collection($members);
    }

    public function store(StoreMemberRequest $request)
    {
        $member = Member::create($request->validated());

        return new MemberResource($member);
    }

    public function show(string $id)
    {
        return new MemberResource(Member::findOrFail($id));
    }

    public function update(UpdateMemberRequest $request, string $id)
    {
        $member = Member::findOrFail($id);
        $member->update($request->validated());

        return new MemberResource($member);
    }

    public function destroy(string $id)
    {
        Member::findOrFail($id)->delete();

        return response()->json(['message' => 'Socio eliminado correctamente']);
    }
}
