<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketsCreate extends Component
{
    public $user_name, $user_phone, $user_description;
    public $ticket_submitted = false;
    public $ticket_number;
    public $showTicketsModal = false;
    public $userTickets = [];

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
        $this->userTickets = Ticket::where('user_email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showTicketsModal = true;
    }

    public function closeModal()
    {
        $this->showTicketsModal = false;
    }
}
