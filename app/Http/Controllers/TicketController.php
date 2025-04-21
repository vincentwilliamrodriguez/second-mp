<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('is_hidden', false);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->oldest()->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => ['required', 'string', 'regex:/^(\+63|0)\d{10}$/'],
            'user_description' => 'required|string',
        ]);

        $validated['ticket_number'] = $this->generateTicketNumber();
        $validated['status'] = 'pending';
        $validated['is_hidden'] = false;

        Ticket::create($validated);

        Log::info('Ticket Submitted: ', $validated);

        return back()->with([
            'ticket_submitted' => true,
            'ticket_number' => $validated['ticket_number'],
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,done',
        ]);

        $ticket->status = $validated['status'];
        $ticket->save();

        return redirect()->route('tickets.index')
            ->with('message', 'Ticket status updated successfully.');
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        if ($request->action === 'delete' && auth()->user()->hasRole('admin')) {
            $ticket->delete();
            return redirect()->route('tickets.index')
                ->with('message', 'Ticket has been permanently deleted.');
        } else {
            $ticket->update(['is_hidden' => true]);
            return redirect()->route('tickets.index')
                ->with('message', 'Ticket has been marked as complete and hidden.');
        }
    }

    private function generateTicketNumber()
    {
        return 'TKT-' . date('Ym') . '-' . strtoupper(Str::random(6));
    }
}
