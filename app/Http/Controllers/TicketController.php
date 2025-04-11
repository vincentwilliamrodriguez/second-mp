<?php

namespace App\Http\Controllers;

use App\Models\Ticket;  // Import the Ticket model if you're working with a database
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        // Fetch tickets from the database or perform other necessary logic
        $tickets = Ticket::all();  // For example, getting all tickets

        // Return a view and pass the tickets to it
        return view('tickets.index', compact('tickets'));
    }
}
