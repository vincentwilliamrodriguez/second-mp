<?php

namespace App\Jobs;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkOrderItemAsShipped implements ShouldQueue
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

        if (!($item && $item->status === 'accepted')) {
            return false;
        }

        $item->status = 'shipped';
        $item->date_shipped = now();
        $item->save();

        MarkOrderItemAsDelivered::dispatch($item->id)->delay(now()->addSeconds(10));
    }
}

