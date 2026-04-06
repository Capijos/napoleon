<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Cart extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'carts';

    protected $fillable = [
        'user_id',
        'identification_id',

        'status',

        'items',

        'subtotal',
        'discount',
        'shipping_cost',
        'tax',
        'total',

        'currency',

        'applied_coupon', // 🔥
    ];

    protected $casts = [
        'items' => 'array',

        'subtotal' => 'float',
        'discount' => 'float',
        'shipping_cost' => 'float',
        'tax' => 'float',
        'total' => 'float',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ABANDONED = 'abandoned';

    protected static function booted()
    {
        static::creating(function ($cart) {
            $cart->status = $cart->status ?? self::STATUS_ACTIVE;
            $cart->currency = $cart->currency ?? 'PEN';
            $cart->items = $cart->items ?? [];
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    /**
     * 🔥 Agregar producto
     */
    public function addItem(array $item)
    {
        // validar estructura mínima
        if (!isset($item['product_id'], $item['quantity'], $item['unit_price'])) {
            throw new \Exception("Item inválido");
        }

        $items = collect($this->items ?? []);

        $index = $items->search(fn ($i) =>
            $i['product_id'] == $item['product_id']
            && ($i['variant_id'] ?? null) == ($item['variant_id'] ?? null)
        );

        if ($index !== false) {
            $items[$index]['quantity'] += $item['quantity'];
            $items[$index]['subtotal'] =
                $items[$index]['quantity'] * $items[$index]['unit_price'];
        } else {
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];
            $items->push($item);
        }

        $this->items = $items->values()->toArray();

        $this->recalculate();
    }

    /**
     * 🔥 Eliminar item
     */
    public function removeItem($productId, $variantId = null)
    {
        $this->items = collect($this->items)
            ->reject(fn ($i) =>
                $i['product_id'] == $productId &&
                ($i['variant_id'] ?? null) == $variantId
            )
            ->values()
            ->toArray();

        $this->recalculate();
    }

    /**
     * 🔥 Aplicar cupón
     */
    public function applyCoupon(Promotion $promo)
    {
        if (!$promo->isValid($this->subtotal)) {
            throw new \Exception("Cupón inválido");
        }

        $this->discount = $promo->apply($this->subtotal);
        $this->applied_coupon = $promo->coupon_code;

        $this->recalculate();
    }

    /**
     * 🔥 Recalcular totales
     */
    public function recalculate()
    {
        $subtotal = collect($this->items)->sum('subtotal');

        $this->subtotal = round($subtotal, 2);

        $this->total = round(
            $this->subtotal
            - ($this->discount ?? 0)
            + ($this->shipping_cost ?? 0)
            + ($this->tax ?? 0),
            2
        );
    }

    /**
     * 🔥 Vaciar carrito
     */
    public function clear()
    {
        $this->items = [];
        $this->subtotal = 0;
        $this->discount = 0;
        $this->total = 0;
    }
}