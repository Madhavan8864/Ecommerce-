<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_gateway',
        'status',
        'payment_details',
        'failure_reason',
        'refund_amount',
        'refund_reason',
        'refunded_at',
        'paid_at',
        'currency'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Methods
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            'partially_refunded' => 'primary'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function markAsCompleted($transactionId = null, $details = null)
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'payment_details' => $details ?? $this->payment_details,
            'paid_at' => now()
        ]);
        
        // Update order payment status
        $this->order->update(['payment_status' => 'completed']);
        
        return $this;
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason
        ]);
        
        return $this;
    }

    public function initiateRefund($amount = null, $reason = null)
    {
        $refundAmount = $amount ?? $this->amount;
        
        if ($refundAmount > $this->amount) {
            throw new \Exception('Refund amount cannot exceed payment amount.');
        }
        
        $this->update([
            'status' => $refundAmount < $this->amount ? 'partially_refunded' : 'refunded',
            'refund_amount' => $refundAmount,
            'refund_reason' => $reason,
            'refunded_at' => now()
        ]);
        
        return $this;
    }

    public function getPaymentDetailsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setPaymentDetailsAttribute($value)
    {
        $this->attributes['payment_details'] = json_encode($value);
    }

    public function isRefundable()
    {
        return $this->status === 'completed' && 
               (!$this->refunded_at || $this->status === 'partially_refunded');
    }

    public function getRemainingRefundableAmount()
    {
        if ($this->status === 'refunded') {
            return 0;
        }
        
        return $this->amount - ($this->refund_amount ?? 0);
    }
}