<?php

namespace App\Filament\Pages;

use App\Services\ZohoCampaignsService;
use App\Settings\ZohoCampaignsSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;

class ManageZohoCampaignsSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Zoho Campaigns';

    protected static ?int $navigationSort = 3;

    protected static string $settings = ZohoCampaignsSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Newsletter list')
                ->description('Footer newsletter signups are sent to this Zoho Campaigns mailing list. Confirmation and welcome emails are managed inside Zoho Campaigns.')
                ->schema([
                    Toggle::make('enabled')
                        ->label('Enable Zoho Campaigns sync')
                        ->helperText('When off, newsletter still submits and admin notification email still sends. Contacts are not added to Campaigns.'),
                    Select::make('region')
                        ->label('Zoho data center')
                        ->options([
                            'com' => 'Global (zoho.com)',
                            'eu' => 'Europe (zoho.eu)',
                            'in' => 'India (zoho.in)',
                            'com.au' => 'Australia (zoho.com.au)',
                            'jp' => 'Japan (zoho.jp)',
                        ])
                        ->helperText('Must match the region where your Zoho Campaigns account was created.'),
                    TextInput::make('list_key')
                        ->label('Mailing list key')
                        ->helperText('Zoho Campaigns → Contacts → Manage Lists → open your list → Setup → copy List Key. Enable the sign-up form on that list for double opt-in confirmation emails.'),
                    TextInput::make('source')
                        ->label('Contact source label')
                        ->helperText('Shown in Zoho Campaigns as the signup source, e.g. Website Footer.'),
                ]),

            Section::make('OAuth credentials')
                ->description('Create a server-based app at api-console.zoho.com with scope ZohoCampaigns.contact.CREATE and access_type=offline to obtain a refresh token.')
                ->schema([
                    TextInput::make('client_id')
                        ->label('Client ID')
                        ->helperText('From Zoho API Console → your client → Client ID.'),
                    TextInput::make('client_secret')
                        ->label('Client secret')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText('From Zoho API Console. Leave blank when saving to keep the current secret.'),
                    TextInput::make('refresh_token')
                        ->label('Refresh token')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText('Generate once with scope ZohoCampaigns.contact.CREATE and access_type=offline. Leave blank when saving to keep the current token.'),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('Test connection')
                ->action(function () {
                    $result = app(ZohoCampaignsService::class)->testConnection();

                    Notification::make()
                        ->title($result['ok'] ? 'Connection successful' : 'Connection failed')
                        ->body($result['message'])
                        ->{$result['ok'] ? 'success' : 'danger'}()
                        ->send();
                }),
        ];
    }
}
