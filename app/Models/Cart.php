<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

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
            $cart->discount = $cart->discount ?? 0;
            $cart->shipping_cost = $cart->shipping_cost ?? 0;
            $cart->tax = $cart->tax ?? 0;
            $cart->subtotal = $cart->subtotal ?? 0;
            $cart->total = $cart->total ?? 0;
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
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
        if (!isset($item['product_id'], $item['quantity'], $item['unit_price'])) {
            throw new \Exception("Item inválido");
        }

        $itemsArray = $this->items ? (array) $this->items : [];
        $found = false;

        foreach ($itemsArray as &$existingItem) {
            if (isset($existingItem['product_id']) && 
                $existingItem['product_id'] == $item['product_id'] &&
                ($existingItem['variant_id'] ?? null) == ($item['variant_id'] ?? null)) {
                
                $existingItem['quantity'] = ($existingItem['quantity'] ?? 0) + $item['quantity'];
                $existingItem['name'] = $item['name'] ?? ($existingItem['name'] ?? 'Producto');
                $existingItem['image'] = $item['image'] ?? ($existingItem['image'] ?? null);
                $existingItem['sku'] = $item['sku'] ?? ($existingItem['sku'] ?? null);
                $existingItem['variant_name'] = $item['variant_name'] ?? ($existingItem['variant_name'] ?? null);
                $existingItem['unit_price'] = round((float) $item['unit_price'], 2);
                $existingItem['item_id'] = $existingItem['item_id'] ?? Str::uuid()->toString();
                $existingItem['subtotal'] = $existingItem['quantity'] * $existingItem['unit_price'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $item['item_id'] = $item['item_id'] ?? Str::uuid()->toString();
            $item['product_id'] = (string) $item['product_id'];
            $item['variant_id'] = isset($item['variant_id']) && $item['variant_id'] !== '' ? (string) $item['variant_id'] : null;
            $item['quantity'] = max(1, (int) $item['quantity']);
            $item['unit_price'] = round((float) $item['unit_price'], 2);
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];
            $itemsArray[] = $item;
        }

        $this->items = $itemsArray;
        $this->recalculate();
    }

    /**
     * 🔥 Eliminar item
     */
    public function removeItemById(string $itemId)
    {
        $itemsArray = $this->items ? (array) $this->items : [];
        $newItems = [];

        foreach ($itemsArray as $item) {
            if (($item['item_id'] ?? null) !== $itemId) {
                $newItems[] = $item;
            }
        }

        $this->items = $newItems;
        $this->recalculate();
    }

    public function updateItemQuantity(string $itemId, int $quantity): bool
    {
        $itemsArray = $this->items ? (array) $this->items : [];

        foreach ($itemsArray as &$item) {
            if (($item['item_id'] ?? null) !== $itemId) {
                continue;
            }

            if ($quantity <= 0) {
                $this->removeItemById($itemId);
                return true;
            }

            $item['quantity'] = $quantity;
            $item['subtotal'] = round($quantity * (float) ($item['unit_price'] ?? 0), 2);
            $this->items = $itemsArray;
            $this->recalculate();

            return true;
        }

        return false;
    }

    public function itemsCount(): int
    {
        return (int) collect($this->items ?? [])->sum('quantity');
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
        $this->shipping_cost = 0;
        $this->tax = 0;
        $this->total = 0;
    }
}
