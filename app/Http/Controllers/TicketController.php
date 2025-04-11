<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        return view('tickets.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'nullable|string|max:255',
            'user_description' => 'required|string',
        ]);

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Your ticket has been submitted successfully. We will contact you shortly!');
    }
}
