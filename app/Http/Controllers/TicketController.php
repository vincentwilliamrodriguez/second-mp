<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;

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

        $ticketNumber = $this->generateTicketNumber();

        $validated['ticket_number'] = $ticketNumber;

        Ticket::create($validated);

        // Redirect with the ticket number and success status
        return redirect()->route('tickets.index')->with([
            'ticket_submitted' => true,
            'ticket_number' => $ticketNumber
        ]);
    }

    private function generateTicketNumber()
    {
        $prefix = 'TKT-' . date('Ym') . '-';
        $randomString = strtoupper(Str::random(6));

        return $prefix . $randomString;
    }
}
