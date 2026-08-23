<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\ContactPageSettings;
use App\Settings\ContactSeoSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageContactPage extends Page implements HasForms
{
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'Contact Page';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.manage-contact-page';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'content' => app(ContactPageSettings::class)->toArray(),
            'seo' => app(ContactSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Contact Page')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('content.eyebrow')->label('Eyebrow'),
                                TextInput::make('content.heading')->label('Heading'),
                                RichEditor::make('content.description')->columnSpanFull(),
                                Grid::make(2)->schema([
                                    TextInput::make('content.label_address')->label('Address Label'),
                                    TextInput::make('content.label_email')->label('Email Label'),
                                    TextInput::make('content.label_phone')->label('Phone Label'),
                                    TextInput::make('content.label_hours')->label('Hours Label'),
                                ]),
                                TextInput::make('content.label_social')->label('Social Label')->columnSpanFull(),
                                Grid::make(2)->schema([
                                    TextInput::make('content.form_title')->label('Form Title'),
                                    TextInput::make('content.form_subtitle')->label('Form Subtitle'),
                                ]),
                                Textarea::make('content.success_message')->label('Success Message')->rows(3)->columnSpanFull(),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo.meta_title')->label('Meta Title')->maxLength(60),
                                Textarea::make('seo.meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                                Textarea::make('seo.meta_keywords')->label('Meta Keywords')->rows(2),
                                Grid::make(2)->schema([
                                    TextInput::make('seo.canonical_url')->label('Canonical URL'),
                                    Select::make('seo.robots')->label('Robots')
                                        ->options([
                                            'index, follow' => 'Index, Follow (Default)',
                                            'noindex, follow' => 'No Index, Follow',
                                            'index, nofollow' => 'Index, No Follow',
                                            'noindex, nofollow' => 'No Index, No Follow',
                                        ]),
                                ]),
                                TextInput::make('seo.og_title')->label('OG Title')->maxLength(60),
                                Textarea::make('seo.og_description')->label('OG Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.og_image_url', 'contact/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                MediaPicker::forField('seo.twitter_image_url', 'contact/seo')->label('Twitter Image'),
                                Textarea::make('seo.schema_json')->label('Schema.org JSON-LD')->rows(6),
                                Textarea::make('seo.custom_head_scripts')->label('Custom Head Scripts')->rows(4),
                                Textarea::make('seo.custom_body_scripts')->label('Custom Body Scripts')->rows(4),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $content = $data['content'] ?? [];
            $seo = $data['seo'] ?? [];
            $seo = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($seo, 'og_image_url');
            $seo = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($seo, 'twitter_image_url');

            $this->saveSettingsGroup(ContactPageSettings::class, $content);
            $this->saveSettingsGroup(ContactSeoSettings::class, $seo);

            Notification::make()
                ->title('Contact Page saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Contact Page')
                ->danger()
                ->send();
        }
    }
}