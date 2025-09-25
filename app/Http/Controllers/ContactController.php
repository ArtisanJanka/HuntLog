<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Store public contact form submissions
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($data);
        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    // Admin: list contact messages
    public function index()
    {
        // paginate so admin page is manageable
        $messages = Contact::latest()->paginate(15);
        return view('admin.contacts.index', compact('messages'));
    }
}
