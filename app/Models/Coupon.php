<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'description',
        'status'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'usage_limit' => 'integer',
        'per_user_limit' => 'integer',
        'used_count' => 'integer',
    ];

    public function isValid($subtotal = null)
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }
        
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }
        
        if ($subtotal && $this->min_amount && $subtotal < $this->min_amount) {
            return false;
        }
        
        return true;
    }

    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid($subtotal)) {
            return 0;
        }
        
        if ($this->type == 'free_shipping') {
            return 0; // Handled separately
        }
        
        $discount = 0;
        
        if ($this->type == 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
        } else {
            $discount = $this->value;
        }
        
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }
        
        return $discount;
    }
}