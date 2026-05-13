<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'kyc_status',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'failed_login_attempts' => 'integer',
    ];

    protected $appends = [
        'initials',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->name ?? '';
    }

    public function getInitialsAttribute(): string
    {
        if (!$this->name) {
            return '';
        }

        $words = explode(' ', trim($this->name));
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        return $initials;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isKycVerified(): bool
    {
        return $this->kyc_status === 'verified';
    }

    public function kyc(): HasOne
    {
        return $this->hasOne(Kyc::class);
    }

    public function transactionsAsSeller(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function transactionsAsBuyer(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function disputesOpened(): HasMany
    {
        return $this->hasMany(Dispute::class, 'opened_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->where('is_read', false);
    }
}
