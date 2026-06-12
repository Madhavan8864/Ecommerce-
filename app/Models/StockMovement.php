<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'old_quantity',
        'new_quantity',
        'reason',
        'reference_type',
        'reference_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'old_quantity' => 'integer',
        'new_quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function getTypeBadgeAttribute()
    {
        $colors = [
            'in' => 'success',
            'out' => 'danger',
            'adjustment' => 'warning'
        ];
        
        return '<span class="badge bg-' . ($colors[$this->type] ?? 'secondary') . '">' . ucfirst($this->type) . '</span>';
    }

    public function getFormattedQuantityAttribute()
    {
        return ($this->type == 'in' ? '+' : '-') . ' ' . $this->quantity;
    }
}