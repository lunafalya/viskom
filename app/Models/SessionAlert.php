<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionAlert extends Model
{
    protected $fillable = [
        'session_id',
        'type',
        'severity',
        'value',
        'triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'triggered_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(MonitoringSession::class, 'session_id');
    }
}
