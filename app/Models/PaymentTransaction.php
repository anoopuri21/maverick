<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCEEDED = 'succeeded';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REFUNDED = 'refunded';

    public const GATEWAY_MANUAL = 'manual';

    protected $fillable = [
        'payment_plan_id',
        'payment_installment_id',
        'gateway',
        'gateway_reference',
        'gateway_session_id',
        'amount_minor',
        'currency',
        'status',
        'payload',
        'paid_at',
    ];

    protected $casts = [
        'amount_minor' => 'integer',
        'payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id');
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(PaymentInstallment::class, 'payment_installment_id');
    }
}
