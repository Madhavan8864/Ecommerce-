<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_address_id',
        'billing_address_id',
        'subtotal',
        'shipping_cost',
        'tax',
        'total_amount',
        'discount_amount',
        'coupon_code',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'tracking_number',
        'tracking_url',
        'shipping_carrier',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'refund_amount',
        'refund_reason',
        'estimated_delivery_date'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'estimated_delivery_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeHighValue($query, $amount = 500)
    {
        return $query->where('total_amount', '>=', $amount);
    }

    // Methods
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), 7, 6));
        
        return $prefix . '-' . $date . '-' . $random;
    }

    public function getItemsCountAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total_amount, 2);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'return_requested' => 'secondary',
            'refunded' => 'light'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function getPaymentStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'info'
        ];
        
        return $colors[$this->payment_status] ?? 'secondary';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function canBeReturned()
    {
        if ($this->status !== 'delivered') {
            return false;
        }
        
        if (!$this->delivered_at) {
            return false;
        }
        
        // Allow returns within 30 days of delivery
        return $this->delivered_at->diffInDays(now()) <= 30;
    }

    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        
        switch ($newStatus) {
            case 'shipped':
                $this->shipped_at = now();
                break;
            case 'delivered':
                $this->delivered_at = now();
                break;
            case 'cancelled':
                $this->cancelled_at = now();
                break;
        }
        
        $this->save();
        
        // Log status change
        OrderStatusHistory::create([
            'order_id' => $this->id,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $notes,
            'changed_by' => auth()->id() ?? null
        ]);
        
        return $this;
    }

    public function getOrderProgressAttribute()
    {
        $steps = ['pending', 'processing', 'shipped', 'delivered'];
        $currentStep = array_search($this->status, $steps);
        
        return $currentStep !== false ? ($currentStep + 1) * 25 : 0;
    }
}