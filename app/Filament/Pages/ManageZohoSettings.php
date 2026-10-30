<?php

namespace App\Filament\Pages;

use App\Settings\ZohoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageZohoSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Zoho Mail';

    protected static ?int $navigationSort = 2;

    protected static string $settings = ZohoSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Sending')
                ->schema([
                    Toggle::make('enabled')
                        ->label('Enable Zoho SMTP')
                        ->helperText('Turn off to keep the site working without Zoho. Forms still submit; email uses the default Laravel mailer (usually log) instead of Zoho.'),
                    TextInput::make('default_recipient')
                        ->label('Primary recipient (To)')
                        ->email()
                        ->maxLength(254)
                        ->helperText('Main destination for form notifications. If empty or invalid, General Settings → UAE Email is used; if that is empty or invalid, fazil@mbalondon.org.uk is the fallback.'),
                    Repeater::make('additional_recipients')
                        ->label('Additional recipients (BCC)')
                        ->schema([
                            TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->maxLength(254)
                                ->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->addActionLabel('Add recipient')
                        ->helperText('Each address receives a blind copy of every form notification. BCC keeps recipient addresses hidden from one another.')
                        ->columnSpanFull(),
                    TextInput::make('from_name')
                        ->label('From name')
                        ->helperText('Display name on outgoing mail, e.g. Maverick Business Academy. The From address is always the Zoho username below.'),
                    TextInput::make('reply_to')
                        ->label('Reply-To')
                        ->email()
                        ->helperText('Optional. If empty, replies go to the visitor email when the form includes one.'),
                ]),

            Section::make('Zoho SMTP')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('smtp_host')
                            ->label('SMTP host')
                            ->helperText('Zoho Mail: smtp.zoho.com (global), smtp.zoho.eu (EU), smtp.zoho.in (India). Zoho Mail Plus / Workplace often uses smtppro.zoho.com.'),
                        TextInput::make('port')
                            ->label('Port')
                            ->numeric()
                            ->helperText('587 with TLS (recommended) or 465 with SSL.'),
                        Select::make('encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS (STARTTLS, port 587)',
                                'ssl' => 'SSL (implicit, port 465)',
                            ])
                            ->helperText('Must match the port. 587 = TLS, 465 = SSL.'),
                        TextInput::make('zoho_mail_domain')
                            ->label('Zoho mail domain (optional)')
                            ->helperText('Your mailbox domain only, e.g. mbalondon.org.uk. Not required for SMTP. Helps you remember which Zoho org you configured.'),
                    ]),
                    TextInput::make('username')
                        ->label('Zoho username (full email)')
                        ->email()
                        ->helperText('The full Zoho mailbox you send from, e.g. admissions@yourdomain.com. Must match a mailbox in Zoho Mail.'),
                    TextInput::make('password')
                        ->label('App-specific password')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText('Zoho Mail → Security → App Passwords → generate a password for “Mail / Laravel”. Do not use your normal Zoho login password. Leave blank when saving to keep the current password.'),
                ]),
        ]);
    }
}
