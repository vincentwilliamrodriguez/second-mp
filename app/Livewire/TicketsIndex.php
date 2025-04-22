<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;

class TicketsIndex extends Component
{
    public $tickets;
    public $archivedTickets;
    public $activeTab = 'list';
    public $replies = [];
    public $search = '';

    public function mount()
    {
        $this->refreshTickets();
    }

    public function render()
    {
        return view('livewire.tickets-index', [
            'tickets' => $this->tickets,
            'archivedTickets' => $this->archivedTickets,
            'activeTab' => $this->activeTab,
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function deleteTicket($ticketId, $action)
    {
        $ticket = Ticket::findOrFail($ticketId);

        if ($action === 'delete') {
            $ticket->delete();
        } else {
            $ticket->update(['is_hidden' => true]);
        }

        $this->refreshTickets();
    }

    public function restoreTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $ticket->update(['is_hidden' => false]);

        $this->refreshTickets();
    }

    public function submitReply($ticketId)
    {
        $this->validate([
            "replies.$ticketId" => 'required|string|min:3',
        ]);

        TicketReply::create([
            'ticket_id' => $ticketId,
            'message' => $this->replies[$ticketId],
        ]);

        $this->replies[$ticketId] = '';
    }

    private function refreshTickets()
    {
        $this->tickets = Ticket::where('is_hidden', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->archivedTickets = Ticket::where('is_hidden', true)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
