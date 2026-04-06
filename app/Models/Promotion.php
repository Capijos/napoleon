<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Promotion extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'promotions';

    protected $fillable = [
        'name',
        'slug',
        'description',

        'type', // percentage | fixed | free_shipping
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
        'value' => 'float',
        'minimum_amount' => 'float',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Tipos permitidos
     */
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';
    const TYPE_FREE_SHIPPING = 'free_shipping';

    protected static function booted()
    {
        static::creating(function ($promo) {
            $promo->slug = $promo->slug ?? Str::slug($promo->name);
            $promo->is_active = $promo->is_active ?? true;
            $promo->used_count = $promo->used_count ?? 0;
        });

        static::saving(function ($promo) {

            // normalizar coupon
            if ($promo->coupon_code) {
                $promo->coupon_code = strtoupper(trim($promo->coupon_code));
            }

            // validar tipo
            $validTypes = [
                self::TYPE_PERCENTAGE,
                self::TYPE_FIXED,
                self::TYPE_FREE_SHIPPING,
            ];

            if (!in_array($promo->type, $validTypes)) {
                throw new \Exception("Tipo de promoción inválido");
            }
        });
    }

    /**
     * 🔥 Validación completa
     */
    public function isValid(float $cartTotal = 0): bool
    {
        $now = now();

        return $this->is_active
            && (!$this->starts_at || $this->starts_at <= $now)
            && (!$this->ends_at || $this->ends_at >= $now)
            && (!$this->usage_limit || $this->used_count < $this->usage_limit)
            && (!$this->minimum_amount || $cartTotal >= $this->minimum_amount);
    }

    /**
     * 🔥 Aplicar descuento
     */
    public function apply(float $amount): float
    {
        switch ($this->type) {

            case self::TYPE_PERCENTAGE:
                return round($amount * ($this->value / 100), 2);

            case self::TYPE_FIXED:
                return min($this->value, $amount);

            case self::TYPE_FREE_SHIPPING:
                return 0;

            default:
                return 0;
        }
    }
}