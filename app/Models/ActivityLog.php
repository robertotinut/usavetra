<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'store_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Record a new user activity log entry conveniently
     */
    public static function record(string $action, string $description, $subject = null, array $properties = [], ?User $user = null): ?self
    {
        try {
            $currentUser = $user ?? auth()->user();
            $storeId = $currentUser?->store_id;

            return self::create([
                'user_id' => $currentUser?->id,
                'store_id' => $storeId,
                'action' => $action,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? $subject->id : null,
                'description' => $description,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'properties' => !empty($properties) ? $properties : null,
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to record activity log: ' . $e->getMessage());
            return null;
        }
    }
}
