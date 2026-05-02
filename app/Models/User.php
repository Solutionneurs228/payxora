<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\KycStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
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
        'is_active' => 'boolean',
        'failed_login_attempts' => 'integer',
        'role' => UserRole::class,
    ];

    // Accessor pour name (si first_name/last_name existent)
    public function getNameAttribute($value)
    {
        if ($value) {
            return $value;
        }
        $first = $this->attributes['first_name'] ?? '';
        $last = $this->attributes['last_name'] ?? '';
        return trim("{$first} {$last}") ?: 'Utilisateur';
    }

    // Mutator pour name (decompose en first_name/last_name si necessaire)
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        // Si la DB a first_name/last_name, on les remplit aussi
        if (Schema::hasColumn('users', 'first_name')) {
            $parts = explode(' ', $value, 2);
            $this->attributes['first_name'] = $parts[0] ?? '';
            $this->attributes['last_name'] = $parts[1] ?? '';
        }
    }

    // Relations
    public function kycProfile()
    {
        return $this->hasOne(KycProfile::class);
    }

    public function transactionsAsSeller()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function transactionsAsBuyer()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function escrowAccounts()
    {
        return $this->hasMany(EscrowAccount::class);
    }

    // Helpers roles
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isSeller(): bool
    {
        return $this->role === UserRole::SELLER || $this->role === UserRole::ADMIN;
    }

    public function isBuyer(): bool
    {
        return $this->role === UserRole::BUYER || $this->role === UserRole::ADMIN;
    }

    // KYC
    public function isKycVerified(): bool
    {
        return $this->kycProfile && $this->kycProfile->status === KycStatus::APPROVED;
    }

    public function isKycPending(): bool
    {
        return $this->kycProfile && $this->kycProfile->status === KycStatus::PENDING;
    }

    // Securite
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function recordFailedLogin(): void
    {
        $this->increment('failed_login_attempts');

        if ($this->failed_login_attempts >= config('payxora.security.max_login_attempts', 5)) {
            $this->update([
                'locked_until' => now()->addMinutes(config('payxora.security.lockout_duration_minutes', 15)),
                'failed_login_attempts' => 0,
            ]);
        }
    }

    public function recordSuccessfulLogin(string $ip): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    // Notifications
    public function routeNotificationForBrevo()
    {
        return $this->email;
    }
}
