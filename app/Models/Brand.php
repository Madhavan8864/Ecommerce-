<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'established_year',
        'country_of_origin'
    ];

    protected $casts = [
        'status' => 'string',
        'established_year' => 'integer',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithProductCount($query)
    {
        return $query->withCount(['products' => function($q) {
            $q->where('is_active', true)->where('status', 'in_stock');
        }]);
    }

    // Methods
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        return asset('images/default-brand.png');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function productCount()
    {
        return $this->products()->count();
    }

    public function getActiveProductCountAttribute()
    {
        return $this->products()->where('is_active', true)->where('status', 'in_stock')->count();
    }
}