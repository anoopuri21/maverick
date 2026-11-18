<?php

namespace App\Filament\Resources\PaymentPlanResource\Pages;

use App\Filament\Resources\PaymentPlanResource;
use App\Models\PaymentPlan;
use App\Payments\PaymentPlanService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentPlan extends CreateRecord
{
    protected static string $resource = PaymentPlanResource::class;

    /**
     * The plan and its schedule are created by the service, so the installment
     * maths lives in one place rather than in this page.
     */
    protected function handleRecordCreation(array $data): PaymentPlan
    {
        $totalMinor = (int) round(((float) $data['total_amount']) * 100);

        $plan = app(PaymentPlanService::class)->create([
            'student_name' => $data['student_name'],
            'student_email' => $data['student_email'],
            'student_phone' => $data['student_phone'] ?? null,
            'program_title' => $data['program_title'],
            'total_minor' => $totalMinor,
            'installment_count' => (int) $data['installment_count'],
            'first_due_date' => $data['first_due_date'],
            'notes' => $data['notes'] ?? null,
        ]);

        Notification::make()
            ->title('Payment plan created')
            ->body('Send the link: '.url('/pay/'.$plan->token))
            ->success()
            ->persistent()
            ->send();

        return $plan;
    }

    protected function getRedirectUrl(): string
    {
        return PaymentPlanResource::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
