<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'user_id', 'method', 'amount', 'fees',
        'provider_reference', 'status', 'provider_response', 'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function user() { return $this->belongsTo(User::class); }
}
