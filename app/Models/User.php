<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'role' => UserRole::class,
        ];
    }

    public function kycProfile(): HasOne
    {
        return $this->hasOne(KycProfile::class);
    }

    public function transactionsAsBuyer(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function transactionsAsSeller(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function allTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id')
            ->union($this->hasMany(Transaction::class, 'buyer_id')->toBase());
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function escrowAccount(): HasOne
    {
        return $this->hasOne(EscrowAccount::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isSeller(): bool
    {
        return $this->role === UserRole::SELLER;
    }

    public function isBuyer(): bool
    {
        return $this->role === UserRole::BUYER;
    }

    public function isKycVerified(): bool
    {
        return $this->kycProfile !== null
            && $this->kycProfile->status === \App\Enums\KycStatus::APPROVED;
    }

    public function isKycPending(): bool
    {
        return $this->kycProfile !== null
            && $this->kycProfile->status === \App\Enums\KycStatus::PENDING;
    }

    public function canTransact(): bool
    {
        return $this->is_active && $this->isKycVerified();
    }
}
