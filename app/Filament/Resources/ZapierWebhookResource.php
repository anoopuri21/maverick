<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZapierWebhookResource\Pages;
use App\Models\ZapierWebhook;
use App\Services\ZapierWebhookDispatcher;
use App\Support\ZapierEvents;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ZapierWebhookResource extends Resource
{
    protected static ?string $model = ZapierWebhook::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Zapier Webhooks';

    protected static ?string $modelLabel = 'Zapier webhook';

    protected static ?string $pluralModelLabel = 'Zapier webhooks';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Webhook connection')
                    ->description('Create the Zap in Zapier first (Webhooks by Zapier → Catch Hook), then paste the webhook URL here. No code changes are needed to connect a new Zap.')
                    ->schema([
                        Forms\Components\Select::make('event_key')
                            ->label('Event')
                            ->options(ZapierEvents::options())
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('label')
                            ->label('Label (optional)')
                            ->placeholder('e.g. CRM lead sync')
                            ->maxLength(255)
                            ->helperText('Internal note so you remember what this Zap does.'),
                        Forms\Components\TextInput::make('url')
                            ->label('Webhook URL')
                            ->url()
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Paste the URL from Zapier’s “Webhooks by Zapier” trigger.'),
                        Forms\Components\Toggle::make('is_enabled')
                            ->label('Enabled')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_key')
                    ->label('Event')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ZapierEvents::options()[$state] ?? $state)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->limit(40)
                    ->copyable()
                    ->tooltip(fn (ZapierWebhook $record): string => $record->url),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('Enabled')
                    ->boolean(),
                Tables\Columns\TextColumn::make('last_status')
                    ->label('Last status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : 'Never'),
                Tables\Columns\TextColumn::make('last_triggered_at')
                    ->label('Last triggered')
                    ->dateTime()
                    ->placeholder('Never')
                    ->sortable(),
            ])
            ->defaultSort('event_key')
            ->actions([
                Tables\Actions\Action::make('sendTest')
                    ->label('Send test')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (ZapierWebhook $record): void {
                        $result = app(ZapierWebhookDispatcher::class)->test($record);

                        Notification::make()
                            ->title($result['ok'] ? 'Test sent' : 'Test failed')
                            ->body($result['message'])
                            ->{$result['ok'] ? 'success' : 'danger'}()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZapierWebhooks::route('/'),
            'create' => Pages\CreateZapierWebhook::route('/create'),
            'edit' => Pages\EditZapierWebhook::route('/{record}/edit'),
        ];
    }
}
