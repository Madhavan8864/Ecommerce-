<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'session_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
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

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeWithProductDetails($query)
    {
        return $query->with(['product' => function($q) {
            $q->select('id', 'name', 'slug', 'sku', 'price', 'discount_price', 'quantity', 'main_image', 'status', 'is_active');
        }]);
    }

    // Methods
    public function getItemPriceAttribute()
    {
        return $this->product->discount_price ?? $this->product->price;
    }

    public function getItemTotalAttribute()
    {
        return $this->item_price * $this->quantity;
    }

    public function getIsAvailableAttribute()
    {
        return $this->product->canAddToCart($this->quantity);
    }

    public function getStockStatusAttribute()
    {
        if (!$this->product->is_active || $this->product->status !== 'in_stock') {
            return 'unavailable';
        }
        
        if ($this->quantity > $this->product->quantity) {
            return 'insufficient';
        }
        
        return 'available';
    }

    public function updateQuantity($newQuantity)
    {
        if ($newQuantity < 1) {
            return false;
        }
        
        $this->quantity = $newQuantity;
        return $this->save();
    }

    public static function mergeGuestCart($userId, $sessionId)
    {
        $guestCartItems = self::bySession($sessionId)->get();
        
        foreach ($guestCartItems as $item) {
            $existingItem = self::byUser($userId)
                ->where('product_id', $item->product_id)
                ->first();
            
            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $item->quantity
                ]);
                $item->delete();
            } else {
                $item->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
            }
        }
        
        return true;
    }
}