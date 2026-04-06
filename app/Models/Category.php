<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Category extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'path',
        'level',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
        'path' => 'array',
    ];

    /**
     * Boot automático
     */
    protected static function booted()
    {
        static::creating(function ($category) {

            // normalizar nombre
            $category->name = trim($category->name);

            // slug automático
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }

            $category->is_active = $category->is_active ?? true;
            $category->sort_order = $category->sort_order ?? 0;

            self::buildTree($category);
        });

        static::updating(function ($category) {

            // si cambia el padre → recalcular árbol
            if ($category->isDirty('parent_id')) {
                self::buildTree($category);
            }
        });
    }

    /**
     * 🔥 Construir árbol
     */
    protected static function buildTree($category)
    {
        if ($category->parent_id) {
            $parent = self::find($category->parent_id);

            if ($parent) {
                $category->path = array_merge($parent->path ?? [], [$parent->_id]);
                $category->level = ($parent->level ?? 0) + 1;
                return;
            }
        }

        $category->path = [];
        $category->level = 0;
    }

    /**
     * Padre
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', '_id');
    }

    /**
     * Hijos
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', '_id');
    }

    /**
     * 🔥 Descendientes
     */
    public function descendants($maxDepth = null)
    {
        $query = self::where('path', $this->_id);

        if ($maxDepth !== null) {
            $query->where('level', '<=', $this->level + $maxDepth);
        }

        return $query->get();
    }

    /**
     * 🔥 Ancestros (breadcrumbs)
     */
    public function ancestors()
    {
        return self::whereIn('_id', $this->path)->get();
    }
}