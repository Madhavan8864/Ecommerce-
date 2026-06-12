<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'display_order'
    ];

    protected $casts = [
        'status' => 'string',
        'display_order' => 'integer',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithProductCount($query)
    {
        return $query->withCount(['products' => function($q) {
            $q->where('is_active', true)->where('status', 'in_stock');
        }]);
    }

    // Methods
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return asset('images/default-category.png');
    }

    public function getFullPathAttribute()
    {
        $path = [];
        $category = $this;
        
        while ($category) {
            $path[] = $category->name;
            $category = $category->parent;
        }
        
        return implode(' > ', array_reverse($path));
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function hasProducts()
    {
        return $this->products()->count() > 0;
    }
}