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

        $this->tickets = Ticket::where('is_hidden', false)
            ->where('ticket_number', 'like', '%'.$this->search.'%')
            ->orWhere('user_name', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->archivedTickets = Ticket::where('is_hidden', true)
            ->where('ticket_number', 'like', '%'.$this->search.'%')
            ->orWhere('user_name', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'asc')
            ->get();
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

    private function refreshTickets()
    {
        $this->tickets = Ticket::where('is_hidden', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $this->archivedTickets = Ticket::where('is_hidden', true)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function searchTickets()
    {
        if (empty($this->search)) {
            $this->refreshTickets();
        } else {
            $this->tickets = Ticket::where('is_hidden', false)
                ->where(function($query) {
                    $query->where('ticket_number', 'like', '%'.$this->search.'%')
                        ->orWhere('user_name', 'like', '%'.$this->search.'%')
                        ->orWhere('user_email', 'like', '%'.$this->search.'%')
                        ->orWhere('user_phone', 'like', '%'.$this->search.'%');
                })
                ->orderBy('created_at', 'asc')
                ->get();


            $this->archivedTickets = Ticket::where('is_hidden', true)
                ->where(function($query) {
                    $query->where('ticket_number', 'like', '%'.$this->search.'%')
                        ->orWhere('user_name', 'like', '%'.$this->search.'%')
                        ->orWhere('user_email', 'like', '%'.$this->search.'%')
                        ->orWhere('user_phone', 'like', '%'.$this->search.'%');
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }
    }

}
