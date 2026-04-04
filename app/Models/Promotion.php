<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'value',
        'coupon_code',
        'minimum_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product')
            ->withTimestamps();
    }
}