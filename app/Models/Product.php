<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use HasUuids;

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
    }

}
