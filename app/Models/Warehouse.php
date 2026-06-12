<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'contact_person',
        'contact_email',
        'contact_phone',
        'capacity',
        'temperature_controlled',
        'hazmat_certified',
        'notes',
        'status'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'temperature_controlled' => 'boolean',
        'hazmat_certified' => 'boolean',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    public function getCapacityUsedAttribute()
    {
        // Calculate based on products stored
        return StockMovement::where('warehouse_id', $this->id)
            ->where('type', 'in')
            ->sum('quantity');
    }

    public function getCapacityPercentageAttribute()
    {
        if (!$this->capacity) {
            return 0;
        }
        
        return round(($this->capacity_used / $this->capacity) * 100, 2);
    }
}