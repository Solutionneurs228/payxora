<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeMessage extends Model
{
    use HasFactory;
    protected $table = 'dispute_messages';
    protected $fillable = ['dispute_id', 'user_id', 'message', 'attachment', 'is_admin'];
    public function dispute() { return $this->belongsTo(Dispute::class); }
    public function user() { return $this->belongsTo(User::class); }
}
