<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory, SoftDeletes;


    protected $table = 'order_items';
    protected $keyType = 'string';
    public $incrementing = 'false';

    protected $fillable = [
        'order_id',
        'product_id',
        'order_quantity',
        'product_price',
        'date_placed',
        'date_accepted',
        'date_shipped',
        'date_delivered',
        'status',
    ];
    protected $casts = [
        'date_placed' => 'date',
        'date_accepted' => 'date',
        'date_shipped' => 'date',
        'date_delivered' => 'date',
    ];


    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
