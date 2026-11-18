<?php

namespace App\Filament\Resources\PaymentPlanResource\RelationManagers;

use App\Models\PaymentInstallment;
use App\Payments\PaymentPlanService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';

    protected static ?string $title = 'Installments';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('amount_minor')
                ->label('Amount (minor units)')
                ->numeric()
                ->disabled()
                ->dehydrated(false),
            Forms\Components\DatePicker::make('due_date')
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sequence')
            ->columns([
                Tables\Columns\TextColumn::make('sequence')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_minor')
                    ->label('Amount')
                    ->formatStateUsing(fn (PaymentInstallment $record): string => $record->plan->amountForDisplay($record->amount_minor)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        PaymentInstallment::STATUS_PAID => 'success',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid on')
                    ->dateTime('d M Y')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('paid_via')
                    ->label('Via')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->limit(24)
                    ->placeholder('—'),
            ])
            ->defaultSort('sequence')
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PaymentInstallment $record): bool => ! $record->isPaid())
                    ->form([
                        Forms\Components\Select::make('via')
                            ->label('Received via')
                            ->options([
                                'bank_transfer' => 'Bank transfer',
                                'cash' => 'Cash',
                                'cheque' => 'Cheque',
                                'other' => 'Other',
                            ])
                            ->default('bank_transfer')
                            ->required(),
                        Forms\Components\TextInput::make('reference')
                            ->label('Reference')
                            ->maxLength(190)
                            ->helperText('Bank reference, receipt number or approval note.'),
                    ])
                    ->action(function (PaymentInstallment $record, array $data): void {
                        app(PaymentPlanService::class)->markPaid(
                            $record,
                            $data['via'],
                            $data['reference'] ?? null,
                        );

                        Notification::make()
                            ->title('Installment marked paid')
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([])
            ->bulkActions([]);
    }
}
