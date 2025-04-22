<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model {
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'customer_id',
        'full_name',
        'phone_number',
        'address',
        'barangay',
        'city',
        'province',
        'postal_code',
        'delivery_method',
        'payment_method',
        'subtotal',
        'shipping_fee',
        'tax',
        'total_amount',
    ];
    protected $appends = ['display_name'];


    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItemsForSeller($sellerId) {
        return $this->hasMany(OrderItem::class)
            ->whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->get();
    }

    public function orderItemsWrapper() {
        return (auth()->user()->hasRole('seller'))
            ? $this->orderItemsForSeller(auth()->id())
            : $this->orderItems;
    }


    public function orderItemsStatuses() {
        return $this->orderItems()->pluck('status');
    }

    protected function overallStatus(): Attribute {
        return Attribute::make(
            get: function () {
                $statuses = $this->orderItemsWrapper()->pluck('status');

                if ($statuses->every(fn($s) => in_array($s, ['delivered', 'cancelled']))) return 'completed';
                if ($statuses->contains('pending')) return 'pending';

                return 'in_progress';
            },
        );
    }


    public function getDisplayNameAttribute() {
        return 'ORD-' . strtoupper(substr($this->id, 0, 8));
    }


    public static function booted() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });

        static::deleting(function ($order) {
            if ($order->isForceDeleting()) {
                $order->orderItems()->forceDelete();
            } else {
                $order->orderItems()->delete();
            }
        });
    }
}
