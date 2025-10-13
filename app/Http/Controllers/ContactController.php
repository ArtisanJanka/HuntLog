<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\HuntingType;
use App\Models\Group;

class ContactController extends Controller
{
    /**
     * GET /contacts — show page with two tabs:
     *  - Contact form
     *  - Join a group (needs types + groups)
     */
    public function create()
    {
        // Eager-load leader and hunting type so Blade can show names
        $types  = HuntingType::orderBy('name')->get(['id','name']);
        $groups = Group::with([
                'leader:id,name,email',
                'huntingType:id,name',
            ])
            ->orderBy('name')
            ->get(['id','name','leader_id','hunting_type_id']);

        return view('contacts', compact('types','groups'));
    }

    /**
     * POST /contacts — handle general contact form submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        Contact::create($data);

        return back()->with('success', 'Ziņa veiksmīgi aizsūtīta!');
    }

    /**
     * (Admin) GET /admin/messages — list contact messages.
     */
    public function index()
    {
        $messages = Contact::latest()->paginate(15);
        return view('admin.contacts.index', compact('messages'));
    }
}
