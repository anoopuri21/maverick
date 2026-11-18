<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentInstallment extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'payment_plan_id',
        'sequence',
        'amount_minor',
        'due_date',
        'status',
        'paid_at',
        'paid_via',
        'reference',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'amount_minor' => 'integer',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue(): bool
    {
        return ! $this->isPaid() && $this->due_date->isPast();
    }
}
