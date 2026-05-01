<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\UserRole;
use App\Enums\KycStatus;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'kyc_status',
        'profile_photo',
        'id_document',
        'address',
        'city',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
         'role' => UserRole::class,
    ];

    protected $appends = ['full_name', 'initials'];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // public function isSeller(): bool
    // {
    //     return $this->role === 'seller';
    // }

    public function isSeller(): bool
{
    return in_array($this->role, ['seller', 'admin']);
}




    public function kycProfile()
{
    return $this->hasOne(KycProfile::class);
}


    public function isKycVerified(): bool
    {
        return $this->kyc_status === 'verified';
    }
public function isKycApproved(): bool
{
    return $this->kycProfile && $this->kycProfile->status === KycStatus::APPROVED;
}
    public function transactionsAsSeller()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function transactionsAsBuyer()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function disputesOpened()
    {
        return $this->hasMany(Dispute::class, 'opened_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}
