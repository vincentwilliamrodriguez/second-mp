<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;

class TicketsIndex extends Component
{
    public $tickets;

    public function mount()
    {
        $this->tickets = Ticket::where('is_hidden', false)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function deleteTicket($ticketId, $action)
    {
        $ticket = Ticket::findOrFail($ticketId);

        if ($action === 'delete' && auth()->user()->hasRole('admin')) {
            $ticket->delete();
        } else {
            $ticket->update(['is_hidden' => true]);
        }

        $this->tickets = Ticket::where('is_hidden', false)->orderBy('created_at', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.tickets-index');
    }
}
