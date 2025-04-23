<?php

namespace App\Jobs;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class MarkOrderItemAsDelivered implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $itemId;

    public function __construct($itemId)
    {
        $this->itemId = $itemId;
    }

    public function handle()
    {
        $item = OrderItem::findOrFail($this->itemId);

        if (!($item && $item->status === 'shipped')) {
            return false;
        }

        $item->status = 'delivered';
        $item->date_delivered = now();
        $item->save();
    }
}

