<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    protected $fillable = [
        'order_id',
        'carrier',
        'tracking_number',
        'tracking_url',
        'status',
        'last_location',
        'last_update',
    ];

    protected $casts = [
        'last_update' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}