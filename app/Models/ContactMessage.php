<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'replied_at',
        'replied_by'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    public function markAsReplied($adminId = null, $notes = null)
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
            'replied_by' => $adminId,
            'admin_notes' => $notes
        ]);
    }

    public function isUnread()
    {
        return $this->status === 'unread';
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}