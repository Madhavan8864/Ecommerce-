<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithProductDetails($query)
    {
        return $query->with(['product' => function($q) {
            $q->select('id', 'name', 'slug', 'price', 'discount_price', 'main_image', 'status', 'is_active');
        }]);
    }

    // Methods
    public static function toggle($userId, $productId)
    {
        $existing = self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        
        if ($existing) {
            $existing->delete();
            return 'removed';
        } else {
            self::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return 'added';
        }
    }

    public static function countByUser($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    public function getProductPriceAttribute()
    {
        return $this->product->discount_price ?? $this->product->price;
    }

    public function getIsInStockAttribute()
    {
        return $this->product->is_active && $this->product->status === 'in_stock';
    }
}