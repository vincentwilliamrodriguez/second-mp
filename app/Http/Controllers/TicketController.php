<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'nullable|string|max:255',
            'user_description' => 'required|string',
        ]);

        $validated['ticket_number'] = $this->generateTicketNumber();
        $validated['status'] = 'open';
        $validated['is_hidden'] = false;

        Ticket::create($validated);

        return back()->with([
            'ticket_submitted' => true,
            'ticket_number' => $validated['ticket_number'],
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated= $request->validate([
            'status' => 'required|in:pending,in_progress,done',
        ]);

        $ticket->status = $validated['status'];

        $ticket->save();
            return redirect()->route('tickets.index')
                ->with('message', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket) {
        $ticket->delete();

        return redirect()->route('tickets.index')
        ->with('message', 'Ticket has been removed successfully.');
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post') && $request->has('ticket_id')) {
            $ticket = Ticket::findOrFail($request->ticket_id);

            if ($request->action === 'delete') {
                $ticket->update(['is_hidden' => true]);
            } else {
                $request->validate([
                    'status' => 'required|string|in:pending,in_progress,done',
                ]);
                $ticket->update(['status' => $request->status]);
            }

            return back()->with('success', 'Ticket updated.');
        }

        $query = Ticket::where('is_hidden', false);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->oldest()->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    private function generateTicketNumber()
    {
        return 'TKT-' . date('Ym') . '-' . strtoupper(Str::random(6));
    }
}

