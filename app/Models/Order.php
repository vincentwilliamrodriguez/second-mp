<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
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

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getDisplayNameAttribute() {
        return 'ORD-' . $this->created_at->format('Ymd') . '-' . strtoupper(substr($this->id, 0, 8));
    }



    public static function booted() {
        parent::boot();

        static::creating(function($model) {
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
