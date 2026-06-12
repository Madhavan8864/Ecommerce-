<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Methods
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total, 2);
    }

    public function updateQuantity($newQuantity)
    {
        $this->quantity = $newQuantity;
        $this->total = $this->price * $newQuantity;
        $this->save();
        
        // Update order totals
        $this->order->updateTotals();
        
        return $this;
    }

    public function canBeReturned()
    {
        if (!$this->order->canBeReturned()) {
            return false;
        }
        
        // Check if already returned
        $returnedQuantity = ReturnItem::whereHas('orderItem', function($q) {
            $q->where('id', $this->id);
        })->sum('quantity');
        
        return $this->quantity > $returnedQuantity;
    }
}