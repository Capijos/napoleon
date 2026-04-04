<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'document_type',
        'document_number',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'country',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}