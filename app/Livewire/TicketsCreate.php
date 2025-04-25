<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketsCreate extends Component
{
    public $ticket;
    public $user_name, $user_phone, $user_description;
    public $ticket_submitted = false;
    public $ticket_number;
    public $showTicketsModal = false;
    public $userTickets = [];
    public $replyingTo = null;
    public $replyText = '';

    protected $rules = [
        'user_name' => 'required|string|max:255',
        'user_phone' => ['required', 'string', 'regex:/^(\+63|0)\d{10}$/'],
        'user_description' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.tickets-create');
    }

    public function submitTicket()
    {
        $this->validate();

        $this->ticket_number = 'TKT-' . date('Ym') . '-' . strtoupper(Str::random(6));

        Ticket::create([
            'user_name' => $this->user_name,
            'user_email' => Auth::user()->email,
            'user_phone' => $this->user_phone,
            'user_description' => $this->user_description,
            'ticket_number' => $this->ticket_number,
            'is_hidden' => false,
        ]);

        $this->ticket_submitted = true;
    }

    public function resetForm()
    {
        $this->reset([
            'user_name',
            'user_phone',
            'user_description',
            'ticket_submitted',
            'ticket_number',
        ]);
    }

    public function showUserTickets()
    {
        $this->userTickets = Ticket::with('replies')
            ->where('user_email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showTicketsModal = true;
    }

    public function closeModal()
    {
        $this->showTicketsModal = false;
        $this->replyingTo = null;
        $this->replyText = '';
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

        TicketReply::create([
            'ticket_id' => $this->replyingTo,
            'message' => $this->replyText,
            'is_from_staff' => false,
            'is_read' => false
        ]);

        $this->userTickets = Ticket::with('replies')
            ->where('user_email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->replyingTo = null;
        $this->replyText = '';
    }
}
