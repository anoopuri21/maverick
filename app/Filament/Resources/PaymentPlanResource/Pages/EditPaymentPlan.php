<?php

namespace App\Filament\Resources\PaymentPlanResource\Pages;

use App\Filament\Resources\PaymentPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentPlan extends EditRecord
{
    protected static string $resource = PaymentPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('open_link')
                ->label('Open payment page')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn (): string => url('/pay/'.$this->getRecord()->token))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return PaymentPlanResource::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
