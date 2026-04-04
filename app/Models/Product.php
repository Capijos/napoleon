<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'shopify_id',
        'shopify_handle',
        'shopify_url',
        'name',
        'slug',
        'description',
        'short_description',
        'brand',
        'material',
        'color',
        'weight',
        'length',
        'thickness',
        'main_image',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'badge_labels',
        'status_badges',
        'raw_product',
    ];

    protected $casts = [
        'badge_labels' => 'array',
        'status_badges' => 'array',
        'raw_product' => 'array',
        'is_featured' => 'boolean',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'thickness' => 'decimal:2',
    ];

public function category()
{
    return $this->belongsTo(Category::class);
}

public function categories()
{
    return $this->belongsToMany(Category::class);
}

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}