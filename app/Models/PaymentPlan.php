<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PaymentPlan extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'token',
        'student_name',
        'student_email',
        'student_phone',
        'program_title',
        'currency',
        'total_minor',
        'installment_minor',
        'installment_count',
        'first_due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_minor' => 'integer',
        'installment_minor' => 'integer',
        'installment_count' => 'integer',
        'first_due_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $plan): void {
            if (blank($plan->token)) {
                $plan->token = Str::random(48);
            }
        });
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class)->orderBy('sequence');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class)->latest('id');
    }

    public function paidInstallments(): HasMany
    {
        return $this->installments()->where('status', PaymentInstallment::STATUS_PAID);
    }

    /** Next installment the student should pay, if any. */
    public function nextInstallment(): ?PaymentInstallment
    {
        return $this->installments()
            ->where('status', PaymentInstallment::STATUS_PENDING)
            ->orderBy('due_date')
            ->orderBy('sequence')
            ->first();
    }

    public function paidMinor(): int
    {
        return (int) $this->installments()
            ->where('status', PaymentInstallment::STATUS_PAID)
            ->sum('amount_minor');
    }

    public function remainingMinor(): int
    {
        return max(0, $this->total_minor - $this->paidMinor());
    }

    public function paidCount(): int
    {
        return $this->installments()
            ->where('status', PaymentInstallment::STATUS_PAID)
            ->count();
    }

    public function completionPercent(): int
    {
        if ($this->total_minor <= 0) {
            return 0;
        }

        return (int) min(100, round($this->paidMinor() / $this->total_minor * 100));
    }

    public function isSettled(): bool
    {
        return $this->remainingMinor() === 0;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_ACTIVE && ! $this->isSettled();
    }

    /**
     * A plan stops accepting card payments a set number of days after its last
     * due date, so a link found months later cannot still be paid.
     */
    public function isExpired(): bool
    {
        $lastDue = $this->installments()->max('due_date');

        if ($lastDue === null) {
            return false;
        }

        return CarbonImmutable::parse($lastDue)
            ->addDays((int) config('payments.page.grace_days', 60))
            ->endOfDay()
            ->isPast();
    }

    public function amountForDisplay(int $minor): string
    {
        return number_format($minor / 100, 2).' '.$this->currency;
    }
}
