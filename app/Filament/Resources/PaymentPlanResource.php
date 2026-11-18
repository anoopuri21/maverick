<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentPlanResource\Pages;
use App\Models\PaymentPlan;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentPlanResource extends Resource
{
    protected static ?string $model = PaymentPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Payments';

    protected static ?string $navigationLabel = 'Payment Plans';

    protected static ?string $modelLabel = 'payment plan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Student')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('student_name')
                        ->required()
                        ->maxLength(190),
                    Forms\Components\TextInput::make('student_email')
                        ->email()
                        ->required()
                        ->maxLength(190),
                    Forms\Components\TextInput::make('student_phone')
                        ->tel()
                        ->maxLength(40),
                    Forms\Components\TextInput::make('program_title')
                        ->label('Programme')
                        ->required()
                        ->maxLength(190)
                        ->datalist(fn (): array => Program::query()->orderBy('title')->pluck('title')->all())
                        ->helperText('Start typing to pick an existing programme title.'),
                ]),

            Forms\Components\Section::make('Plan')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('total_amount')
                        ->label('Total (AED)')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->step('0.01')
                        ->visibleOn('create')
                        ->helperText('Whole amount for the programme, for example 3000.'),

                    Forms\Components\TextInput::make('installment_count')
                        ->label('Number of installments')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(24)
                        ->visibleOn('create')
                        ->default(6),

                    Forms\Components\DatePicker::make('first_due_date')
                        ->label('First due date')
                        ->required()
                        ->default(now()->addDays(7))
                        ->visibleOn('create')
                        ->helperText('The rest follow monthly on the same day.'),

                    Forms\Components\Textarea::make('notes')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Link')
                ->visible(fn (?PaymentPlan $record): bool => $record !== null)
                ->schema([
                    Forms\Components\Placeholder::make('payment_link')
                        ->label('Private payment link')
                        ->content(fn (PaymentPlan $record): string => url('/pay/'.$record->token)),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program_title')
                    ->label('Programme')
                    ->limit(38)
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_minor')
                    ->label('Total')
                    ->formatStateUsing(fn (PaymentPlan $record): string => $record->amountForDisplay($record->total_minor)),
                Tables\Columns\TextColumn::make('paid_progress')
                    ->label('Paid')
                    ->getStateUsing(fn (PaymentPlan $record): string => $record->paidCount().' / '.$record->installment_count),
                Tables\Columns\TextColumn::make('remaining')
                    ->label('Remaining')
                    ->getStateUsing(fn (PaymentPlan $record): string => $record->amountForDisplay($record->remainingMinor())),
                Tables\Columns\TextColumn::make('next_due')
                    ->label('Next due')
                    ->getStateUsing(fn (PaymentPlan $record): ?string => $record->nextInstallment()?->due_date->format('d M Y')),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        PaymentPlan::STATUS_COMPLETED => 'success',
                        PaymentPlan::STATUS_CANCELLED => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        PaymentPlan::STATUS_ACTIVE => 'Active',
                        PaymentPlan::STATUS_COMPLETED => 'Completed',
                        PaymentPlan::STATUS_CANCELLED => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('show_link')
                    ->label('Link')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->action(function (PaymentPlan $record): void {
                        Notification::make()
                            ->title('Payment link')
                            ->body(url('/pay/'.$record->token))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('open_link')
                    ->label('Open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (PaymentPlan $record): string => url('/pay/'.$record->token))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            PaymentPlanResource\RelationManagers\InstallmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentPlans::route('/'),
            'create' => Pages\CreatePaymentPlan::route('/create'),
            'edit' => Pages\EditPaymentPlan::route('/{record}/edit'),
        ];
    }
}
