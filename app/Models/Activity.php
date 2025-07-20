<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'title',
        'description',
        'action_url',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log($userId, $type, $title, $description, $icon = 'bell', $metadata = [])
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'icon' => $icon,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}
