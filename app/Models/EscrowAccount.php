<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscrowAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'amount_held', 'status', 'released_at', 'refunded_at',
    ];

    protected $casts = [
        'amount_held' => 'decimal:2',
        'released_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function transaction() { return $this->belongsTo(Transaction::class); }
}
