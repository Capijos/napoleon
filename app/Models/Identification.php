<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Identification extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'identifications';

    protected $fillable = [
        'first_name',
        'last_name',

        'document_type',
        'document_number',

        'phone',
        'email',
    ];

    protected $casts = [
        'document_number' => 'string',
    ];

    /**
     * Tipos permitidos
     */
    const DOC_TYPES = ['dni', 'ruc', 'passport'];

    /**
     * Boot automático
     */
    protected static function booted()
    {
        static::saving(function ($model) {

            // email normalizado
            if ($model->email) {
                $model->email = strtolower(trim($model->email));
            }

            // documento limpio
            if ($model->document_number) {
                $model->document_number = preg_replace('/\D/', '', $model->document_number);
            }

            // tipo documento validado
            if ($model->document_type) {
                $type = strtolower(trim($model->document_type));

                if (!in_array($type, self::DOC_TYPES)) {
                    throw new \Exception("Tipo de documento inválido");
                }

                $model->document_type = $type;
            }

            // teléfono limpio
            if ($model->phone) {
                $model->phone = preg_replace('/\D/', '', $model->phone);
            }
        });
    }

    /**
     * Relación con carritos
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'identification_id', '_id');
    }

    /**
     * Nombre completo
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}