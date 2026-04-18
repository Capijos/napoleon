<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Product extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

protected $fillable = [
    'category_ids',
    'category_slugs',
    'category_names',
    'promotion_ids',
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
    'variants',
    'images',
    'raw_product',
];
protected $casts = [
    'category_ids' => 'array',
    'category_slugs' => 'array',
    'category_names' => 'array',
    'promotion_ids' => 'array',
    'variants' => 'array',
    'images' => 'array',
    'badge_labels' => 'array',
    'status_badges' => 'array',
    'raw_product' => 'array',
    'is_featured' => 'boolean',
    'weight' => 'float',
    'length' => 'float',
    'thickness' => 'float',
];

    /**
     * Boot automático
     */
    protected static function booted()
    {
        static::creating(function ($product) {

            if (!$product->slug) {
                $product->slug = Str::slug($product->name);
            }

            $product->status = $product->status ?? 'active';
            $product->variants = $product->variants ?? [];
            $product->images = $product->images ?? [];
            $product->category_ids = $product->category_ids ?? [];
            $product->promotion_ids = $product->promotion_ids ?? [];
        });

        static::saving(function ($product) {

            // normalizar texto
            if ($product->name) {
                $product->name = trim($product->name);
            }

            if ($product->brand) {
                $product->brand = trim($product->brand);
            }

            // slug limpio
            if ($product->slug) {
                $product->slug = Str::slug($product->slug);
            }
        });
    }

    /**
     * 🔥 Precio mínimo
     */
    public function getMinPriceAttribute()
    {
        return collect($this->variants)->min('price') ?? 0;
    }

    /**
     * 🔥 Precio máximo
     */
    public function getMaxPriceAttribute()
    {
        return collect($this->variants)->max('price') ?? 0;
    }

    /**
     * 🔥 Variante principal (disponible primero)
     */
    public function getDefaultVariantAttribute()
    {
        return collect($this->variants)
            ->where('available', true)
            ->first() ?? collect($this->variants)->first();
    }

    /**
     * 🔥 Está disponible
     */
    public function getIsAvailableAttribute()
    {
        return collect($this->variants)->contains('available', true);
    }

    public function resolveVariant(?string $variantId = null): ?array
    {
        $variants = collect($this->variants ?? [])->filter(fn ($variant) => is_array($variant));

        if ($variantId !== null && $variantId !== '') {
            $variant = $variants->first(function ($candidate) use ($variantId) {
                return (string) ($candidate['variant_id'] ?? '') === (string) $variantId;
            });

            if ($variant) {
                return $variant;
            }

            return null;
        }

        return $variants->first(fn ($variant) => ($variant['available'] ?? false) === true)
            ?? $variants->first();
    }

    public function toCartItemData(?string $variantId = null): array
    {
        $variant = $this->resolveVariant($variantId);
        $resolvedVariantId = $variant['variant_id'] ?? null;
        $variantName = $variant['title'] ?? null;
        $unitPrice = (float) ($variant['price'] ?? $this->min_price ?? 0);
        $image = $this->main_image;

        if (!$image && is_array($this->images) && !empty($this->images[0]['src'])) {
            $image = $this->images[0]['src'];
        }

        if ($variantName === 'Default Title') {
            $variantName = null;
        }

        return [
            'product_id' => (string) $this->getKey(),
            'variant_id' => $resolvedVariantId !== null && $resolvedVariantId !== '' ? (string) $resolvedVariantId : null,
            'name' => (string) $this->name,
            'image' => $image ?: null,
            'sku' => $variant['sku'] ?? null,
            'variant_name' => $variantName,
            'unit_price' => round($unitPrice, 2),
        ];
    }
}
