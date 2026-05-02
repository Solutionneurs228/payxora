<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper pour logger une action
     */
    public static function log(string $action, $subject = null, $user = null, array $metadata = []): self
    {
        return self::create([
            'user_id' => $user?->id,
            'action' => $action,
            'description' => $subject ? class_basename($subject) . ' #' . $subject->id : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => array_merge($metadata, $subject ? ['subject_id' => $subject->id, 'subject_type' => get_class($subject)] : []),
        ]);
    }
}
