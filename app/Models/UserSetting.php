<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'alarm_enabled',
        'visual_warning',
        'dev_mode',
        'dark_mode',
    ];

    protected function casts(): array
    {
        return [
            'alarm_enabled' => 'boolean',
            'visual_warning' => 'boolean',
            'dev_mode' => 'boolean',
            'dark_mode' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
