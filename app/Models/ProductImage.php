<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'shopify_media_id',
        'src',
        'alt',
        'width',
        'height',
        'position',
        'aspect_ratio',
        'media_type',
    ];

    protected $casts = [
        'aspect_ratio' => 'decimal:4',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}