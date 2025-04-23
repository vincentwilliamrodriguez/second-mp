<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;

class TicketsIndex extends Component
{
    public $tickets;
    public $acceptedTickets;
    public $availableTab = 'list';
    public $queryString = ['availableTab'];
    public $search = '';
    public $replyText = '';
    public $replyingTo = null;

    public function mount()
    {
        $this->refreshTickets();
    }

    public function render()
    {
        return view('livewire.tickets-index', [
            'tickets' => $this->tickets,
            'acceptedTickets' => $this->acceptedTickets,
            'availableTab' => $this->availableTab,
        ]);
    }

    public function setTab($tab)
    {
        $this->availableTab = $tab;
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

    private function refreshTickets()
    {
        $this->tickets = Ticket::where('is_hidden', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->acceptedTickets = Ticket::where('is_hidden', true)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function searchEmail()
    {
        $this->tickets = Ticket::where('is_hidden', false)
            ->where('user_email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->acceptedTickets = Ticket::where('is_hidden', true)
            ->where('user_email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function startReply($ticketId)
    {
        $this->replyingTo = $ticketId;
        $this->replyText = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyText = '';
    }

    public function sendReply()
    {
        $this->validate([
            'replyText' => 'required|min:3',
        ]);

        $ticket = Ticket::findOrFail($this->replyingTo);

        TicketReply::create([
            'ticket_id' => $this->replyingTo,
            'message' => $this->replyText,
            'is_from_staff' => true,
            'is_read' => false
        ]);

        $this->replyingTo = null;
        $this->replyText = '';

        session()->flash('message', 'Reply sent successfully!');
    }
}
