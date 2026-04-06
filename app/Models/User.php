<?php

namespace App\Models;

use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'document_type',
        'document_number',
        'avatar',
        'is_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Tipos de documento válidos
     */
    const DOC_TYPES = ['dni', 'ruc', 'passport'];

    /**
     * Boot automático
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->is_active = $user->is_active ?? true;
            $user->is_admin = $user->is_admin ?? false;
        });

        static::saving(function ($user) {

            // email normalizado
            if ($user->email) {
                $user->email = strtolower(trim($user->email));
            }

            // documento limpio
            if ($user->document_number) {
                $user->document_number = preg_replace('/\D/', '', $user->document_number);
            }

            // teléfono limpio
            if ($user->phone) {
                $user->phone = preg_replace('/\D/', '', $user->phone);
            }

            // validar tipo documento
            if ($user->document_type) {
                $type = strtolower($user->document_type);

                if (!in_array($type, self::DOC_TYPES)) {
                    throw new \Exception("Tipo de documento inválido");
                }

                $user->document_type = $type;
            }
        });
    }

    /**
     * 🔥 Nombre completo
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Relaciones
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id', '_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', '_id');
    }

    /**
     * 🔥 Carrito activo (último por seguridad)
     */
    public function activeCart()
    {
        return $this->hasOne(Cart::class, 'user_id', '_id')
            ->where('status', 'active')
            ->latest();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', '_id');
    }

    /**
     * 🔥 Verificar admin
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * 🔥 Scope activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}