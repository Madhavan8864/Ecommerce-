<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'status',
        'approved_by',
        'approved_at',
        'rejected_reason'
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function admin()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    public function scopeWithProduct($query)
    {
        return $query->with('product');
    }

    // Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($adminId = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId ?? auth()->id(),
            'approved_at' => now(),
            'rejected_reason' => null
        ]);
        
        // Update product rating
        $this->product->updateRating();
        
        return $this;
    }

    public function reject($reason = null, $adminId = null)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $adminId ?? auth()->id(),
            'approved_at' => null,
            'rejected_reason' => $reason
        ]);
        
        return $this;
    }

    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-warning"></i>';
            }
        }
        return $stars;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, h:i A');
    }
}