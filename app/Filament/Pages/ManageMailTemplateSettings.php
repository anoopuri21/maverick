<?php

namespace App\Filament\Pages;

use App\Services\FormMailer;
use App\Settings\MailTemplateSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;

class ManageMailTemplateSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Email Template';

    protected static ?int $navigationSort = 4;

    protected static string $settings = MailTemplateSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Header (top of email)')
                ->description('Shown above the form details in every form email (contact, enquiry, newsletter). Leave empty to skip this section completely.')
                ->schema([
                    RichEditor::make('header_html')
                        ->label('Header content')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                            'h2',
                            'h3',
                        ])
                        ->helperText('Tip: logo email ke top par apne aap aata hai — General Settings → Logo (White). Yahan sirf extra text/intro likhein.')
                        ->columnSpanFull(),
                ]),

            Section::make('Footer / Regards (bottom of email)')
                ->description('Shown below the form details — e.g. your regards/signature. Leave empty and the email will have no footer at all (no automatic fallback).')
                ->schema([
                    RichEditor::make('footer_html')
                        ->label('Footer content')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                            'h2',
                            'h3',
                        ])
                        ->helperText('Example: Regards,<br>Maverick Business Academy')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendTestEmail')
                ->label('Send test email')
                ->action(function () {
                    $ok = app(FormMailer::class)->send([
                        'Name' => 'Test User',
                        'Email' => 'test@example.com',
                        'Phone' => '+971 50 000 0000',
                        'Subject' => 'Template preview',
                        'Message' => 'This is a test submission to preview the email template — header, form details and footer.',
                    ], 'Test email — template preview');

                    Notification::make()
                        ->title($ok ? 'Test email sent' : 'Could not send')
                        ->body($ok
                            ? 'Default recipient (Zoho Mail settings) ko bhej diya — inbox check karein.'
                            : 'Email send nahi hui — storage/logs/laravel.log me FormMailer lines dekhein.')
                        ->{$ok ? 'success' : 'danger'}()
                        ->send();
                }),
        ];
    }
}
