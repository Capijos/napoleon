<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'status',
        'payment_status',
        'shipping_status',
        'currency',
        'subtotal',
        'discount',
        'shipping_cost',
        'tax',
        'total',
        'notes',
        'customer_full_name',
        'customer_email',
        'customer_phone',
        'shipping_recipient_name',
        'shipping_phone',
        'shipping_country',
        'shipping_state',
        'shipping_city',
        'shipping_district',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_reference',
        'shipping_postal_code',
        'placed_at',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function shipmentTrackings()
    {
        return $this->hasMany(ShipmentTracking::class);
    }
}