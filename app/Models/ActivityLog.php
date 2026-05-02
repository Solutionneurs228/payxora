<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'entity_type',
        'entity_id',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'entity_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * Log une action avec toutes les infos automatiques.
     */
    public static function log(string $action, $entity = null, ?User $user = null, string $description = null): self
    {
        $data = [
            'action' => $action,
            'description' => $description ?? $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        if ($user) {
            $data['user_id'] = $user->id;
        } elseif (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        if ($entity) {
            $data['entity_type'] = get_class($entity);
            $data['entity_id'] = $entity->id;
        }

        return self::create($data);
    }
}
