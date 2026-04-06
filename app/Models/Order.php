<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Order extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id',
        'identification_id',

        'order_number',

        'status',
        'payment_status',
        'shipping_status',

        'currency',

        'items',

        'subtotal',
        'discount',
        'shipping_cost',
        'tax',
        'total',

        'notes',

        'customer',
        'shipping',
        'tracking',

        'placed_at',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'items' => 'array',
        'customer' => 'array',
        'shipping' => 'array',
        'tracking' => 'array',

        'subtotal' => 'float',
        'discount' => 'float',
        'shipping_cost' => 'float',
        'tax' => 'float',
        'total' => 'float',

        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Estados válidos
     */
    const STATUS = ['pending', 'confirmed', 'cancelled'];
    const PAYMENT_STATUS = ['pending', 'paid', 'failed'];
    const SHIPPING_STATUS = ['pending', 'processing', 'shipped', 'delivered'];

    /**
     * Boot automático
     */
    protected static function booted()
    {
        static::creating(function ($order) {

            // 🔥 order number único real
            do {
                $orderNumber = strtoupper(Str::random(10));
            } while (self::where('order_number', $orderNumber)->exists());

            $order->order_number = $order->order_number ?? $orderNumber;

            $order->status = $order->status ?? 'pending';
            $order->payment_status = $order->payment_status ?? 'pending';
            $order->shipping_status = $order->shipping_status ?? 'pending';

            $order->currency = $order->currency ?? 'PEN';

            $order->items = $order->items ?? [];
            $order->tracking = $order->tracking ?? [];
        });

        static::saving(function ($order) {

            if (!in_array($order->status, self::STATUS)) {
                throw new \Exception("Estado inválido");
            }

            if (!in_array($order->payment_status, self::PAYMENT_STATUS)) {
                throw new \Exception("Estado de pago inválido");
            }

            if (!in_array($order->shipping_status, self::SHIPPING_STATUS)) {
                throw new \Exception("Estado de envío inválido");
            }
        });
    }

    /**
     * Usuario (opcional)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    /**
     * 🔥 Agregar item correctamente
     */
    public function addItem(array $item)
    {
        $this->items = array_merge($this->items ?? [], [$item]);

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
     * 🔥 Marcar como pagado
     */
    public function markAsPaid()
    {
        $this->payment_status = 'paid';
        $this->paid_at = now();
    }

    /**
     * 🔥 Agregar tracking
     */
    public function addTracking(array $tracking)
    {
        $this->push('tracking', array_merge($tracking, [
            'date' => now(),
        ]));
    }
}