<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Server extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'order_id',
        'name',
        'server_id',
        'status',
        'minecraft_version',
        'server_type',
        'ip_address',
        'port',
        'current_players',
        'max_players',
        'memory_usage',
        'max_memory',
        'uptime',
        'monthly_cost',
        'last_online',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'last_online' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'creating' => 'yellow',
            'suspended' => 'red',
            'terminated' => 'gray',
            default => 'gray'
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($server) {
            if (empty($server->server_id)) {
                $server->server_id = 'MC-' . strtoupper(uniqid());
            }
        });
    }
}
