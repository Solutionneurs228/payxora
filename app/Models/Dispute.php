<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'opened_by', 'reason', 'description', 'evidence',
        'status', 'resolved_by', 'resolution_notes', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function opener() { return $this->belongsTo(User::class, 'opened_by'); }
    public function resolver() { return $this->belongsTo(User::class, 'resolved_by'); }
    public function messages() { return $this->hasMany(DisputeMessage::class)->orderBy('created_at'); }

    public function getReasonLabel(): string
    {
        return match($this->reason) {
            'not_received' => 'Non recu',
            'not_as_described' => 'Non conforme a la description',
            'damaged' => 'Produit endommage',
            'wrong_item' => 'Mauvais article',
            'seller_no_ship' => 'Vendeur n\'a pas expedie',
            'other' => 'Autre',
            default => $this->reason,
        };
    }
}
