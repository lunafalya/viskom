<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringSession extends Model
{
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'microsleep_count',
        'perclos_alerts',
        'yawn_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(SessionAlert::class, 'session_id');
    }

    /** Total alert count for this session */
    public function totalAlerts(): int
    {
        return $this->microsleep_count + $this->perclos_alerts + $this->yawn_count;
    }
}
