<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relationship with Products matching by slug.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'slug');
    }

    /**
     * Scope for active categories only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for sorting categories.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Helper to return active category key-value array [slug => name].
     */
    public static function getActiveMap(): array
    {
        try {
            $categories = static::active()->ordered()->pluck('name', 'slug')->toArray();
            return !empty($categories) ? $categories : Product::CATEGORIES;
        } catch (\Throwable $e) {
            return Product::CATEGORIES;
        }
    }
}
