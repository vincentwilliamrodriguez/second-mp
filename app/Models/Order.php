<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'product_id',
        'customer_id',
        'quantity',
        'is_placed',
        'date_placed',
        'status',
    ];
    protected $casts = [
        'date_placed' => 'date',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
