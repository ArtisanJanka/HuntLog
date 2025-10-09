<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupRequest;
use Illuminate\Support\Facades\Auth;

class GroupRequestController extends Controller
{
    public function store(Request $request)
    {
       
        GroupRequest::create([
            'user_id' => auth()->id(),
            'hunting_type_id' => $request->hunting_type_id,
            'group_name' => $request->group_name ?? 'Default Group Name',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Request sent!');
    }
}
