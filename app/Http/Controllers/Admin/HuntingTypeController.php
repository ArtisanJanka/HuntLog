<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HuntingType;
use Illuminate\Http\Request;

class HuntingTypeController extends Controller
{
    public function index()
    {
        $types = HuntingType::latest()->get();
        return view('admin.hunting-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.hunting-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:hunting_types,name|max:255',
        ]);

        HuntingType::create($request->only('name'));

        return redirect()->route('admin.hunting-types.index')->with('success', 'Hunting type created!');
    }

    public function edit(HuntingType $huntingType)
{
    // Pass as 'type' to match your view
    return view('admin.hunting-types.edit', ['type' => $huntingType]);
}

public function update(Request $request, HuntingType $huntingType)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:hunting_types,name,' . $huntingType->id,
    ]);

    $huntingType->update(['name' => $request->name]);

    return redirect()->route('admin.hunting-types.index')->with('success', 'Hunting type updated!');
}


    public function destroy(HuntingType $huntingType)
    {
        $huntingType->delete();
        return redirect()->route('admin.hunting-types.index')->with('success', 'Hunting type deleted!');
    }
}
