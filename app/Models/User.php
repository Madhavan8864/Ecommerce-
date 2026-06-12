<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'profile_completed',
        'two_factor_enabled',
        'two_factor_type',
        'two_factor_secret',
        'provider',
        'provider_id',
        'last_login_at',
        'last_activity',
        'force_password_change'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_activity' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'profile_completed' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'force_password_change' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar_url',
        'full_address',
        'age',
        'profile_completion_percentage',
        'is_phone_verified'
    ];

    // Relationships
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeCustomer($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeWithProfileCompleted($query)
    {
        return $query->where('profile_completed', true);
    }

    public function scopeRegisteredBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Methods
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isCustomer()
    {
        return $this->hasRole('user');
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->address) $parts[] = $this->address;
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state;
        if ($this->zip_code) $parts[] = $this->zip_code;
        if ($this->country) $parts[] = $this->country;
        
        return implode(', ', $parts) ?: null;
    }

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return now()->diffInYears($this->date_of_birth);
    }

    public function getProfileCompletionPercentageAttribute()
    {
        $fields = [
            'name' => 10,
            'email' => 10,
            'phone' => 10,
            'date_of_birth' => 10,
            'gender' => 10,
            'address' => 10,
            'city' => 10,
            'state' => 10,
            'zip_code' => 10,
            'country' => 10,
        ];
        
        $completed = 0;
        
        foreach ($fields as $field => $weight) {
            if (!empty($this->$field)) {
                $completed += $weight;
            }
        }
        
        return min($completed, 100);
    }

    public function getIsPhoneVerifiedAttribute()
    {
        return !is_null($this->phone_verified_at);
    }

    public function getInitialsAttribute()
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';
        
        foreach ($nameParts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }

    // Business Logic Methods
    public function hasUnreadNotifications()
    {
        return $this->unreadNotifications()->count() > 0;
    }

    public function totalSpent()
    {
        return $this->orders()->where('status', 'delivered')->sum('total_amount');
    }

    public function orderCount()
    {
        return $this->orders()->count();
    }

    public function averageOrderValue()
    {
        $orderCount = $this->orderCount();
        if ($orderCount === 0) return 0;
        
        return $this->totalSpent() / $orderCount;
    }

    public function cartItemCount()
    {
        return $this->cart()->sum('quantity');
    }

    public function wishlistItemCount()
    {
        return $this->wishlist()->count();
    }

    public function hasDefaultAddress()
    {
        return $this->addresses()->where('is_default', true)->exists();
    }

    public function getDefaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function hasRecentOrder()
    {
        return $this->orders()
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();
    }

    // Security Methods
    public function requiresPasswordChange()
    {
        return $this->force_password_change || 
               ($this->password_updated_at && $this->password_updated_at->diffInDays(now()) > 90);
    }

    public function enableTwoFactor($type = 'email')
    {
        $this->update([
            'two_factor_enabled' => true,
            'two_factor_type' => $type,
        ]);
    }

    public function disableTwoFactor()
    {
        $this->update([
            'two_factor_enabled' => false,
            'two_factor_type' => null,
            'two_factor_secret' => null,
        ]);
    }

    public function markPhoneAsVerified()
    {
        $this->update(['phone_verified_at' => now()]);
    }

    // Activity Methods
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_activity' => now(),
        ]);
    }

    public function updateLastActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    public function isOnline()
    {
        if (!$this->last_activity) {
            return false;
        }
        
        return $this->last_activity->diffInMinutes(now()) < 5;
    }

    // Profile Methods
    public function markProfileAsCompleted()
    {
        $this->update(['profile_completed' => true]);
    }

    public function updateProfileCompletion()
    {
        $percentage = $this->profile_completion_percentage;
        $isCompleted = $percentage >= 80;
        
        $this->update(['profile_completed' => $isCompleted]);
        
        return $isCompleted;
    }

    // Events
   
}