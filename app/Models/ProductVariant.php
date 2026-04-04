<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'shopify_variant_id',
        'title',
        'sku',
        'price',
        'compare_price',
        'stock',
        'available',
        'option1',
        'option2',
        'option3',
        'requires_shipping',
        'taxable',
        'inventory_management',
        'barcode',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'available' => 'boolean',
        'requires_shipping' => 'boolean',
        'taxable' => 'boolean',
        'options' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}