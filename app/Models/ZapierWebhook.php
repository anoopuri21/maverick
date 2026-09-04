<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ZapierWebhook extends Model
{
    protected $fillable = [
        'event_key',
        'label',
        'url',
        'is_enabled',
        'last_triggered_at',
        'last_status',
        'last_response',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'last_triggered_at' => 'datetime',
    ];

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForEvent(Builder $query, string $eventKey): Builder
    {
        return $query->where('event_key', $eventKey);
    }
}
