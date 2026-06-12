<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code',
        'country',
        'is_default',
        'phone',
        'name'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingOrders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    public function billingOrders()
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    // Scopes
    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    public function scopeBilling($query)
    {
        return $this->where('type', 'billing');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Methods
    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->address_line_1) $parts[] = $this->address_line_1;
        if ($this->address_line_2) $parts[] = $this->address_line_2;
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state;
        if ($this->zip_code) $parts[] = $this->zip_code;
        if ($this->country) $parts[] = $this->country;
        
        return implode(', ', $parts);
    }

    public function getFormattedAddressAttribute()
    {
        $html = '';
        
        if ($this->name) {
            $html .= '<strong>' . e($this->name) . '</strong><br>';
        }
        
        if ($this->address_line_1) {
            $html .= e($this->address_line_1) . '<br>';
        }
        
        if ($this->address_line_2) {
            $html .= e($this->address_line_2) . '<br>';
        }
        
        $cityState = [];
        if ($this->city) $cityState[] = e($this->city);
        if ($this->state) $cityState[] = e($this->state);
        if ($this->zip_code) $cityState[] = e($this->zip_code);
        
        if (!empty($cityState)) {
            $html .= implode(', ', $cityState) . '<br>';
        }
        
        if ($this->country) {
            $html .= e($this->country) . '<br>';
        }
        
        if ($this->phone) {
            $html .= 'Phone: ' . e($this->phone);
        }
        
        return $html;
    }

    public function markAsDefault()
    {
        // Unset other default addresses of same type
        Address::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
    }
}