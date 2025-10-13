<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
        ]);

        $leader = Auth::user();

        return DB::transaction(function () use ($data, $leader) {
            $group = Group::create([
                'hunting_type_id' => $data['hunting_type_id'],
                'leader_id'       => $leader->id,
                'name'            => $data['name'],
                'slug'            => Str::slug($data['name']).'-'.Str::random(6),
                'description'     => $data['description'] ?? null,
            ]);


            $group->members()->attach($leader->id, ['role' => 'co_leader', 'status' => 'active']);

            return redirect()->back()->with('success', 'Grupa izveidota.');
        });
    }


    public function show(Group $group)
    {
        $group->load(['huntingType','leader','members']);
        return view('groups.show', compact('group'));
    }
}

