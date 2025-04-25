<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_email',
        'user_phone',
        'user_description',
        'ticket_number',
        'is_hidden',
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}

