<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'products';
    protected $keyType = 'string';
    public $incrementing = 'false';

    protected $fillable = [
        'name',
        'seller_id',
        'description',
        'category',
        'quantity',
        'price',
        'picture',
    ];

    public static function booted() {
        parent::boot();

        static::creating(function($model) {
            $model->id = Str::uuid();
        });

        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                $product->orders()->forceDelete();
            } else {
                $product->orders()->delete();
            }
        });
    }

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

}
