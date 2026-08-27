<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\SavesSettingsGroups;
use App\Settings\GlobalAccessPointsSettings;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageGlobalAccessPoints extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettingsGroups;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Global Access Points';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.manage-global-access-points';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            safe_settings(GlobalAccessPointsSettings::class)->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Section Header')
                    ->schema([
                        TextInput::make('label')->label('Section Label'),
                        TextInput::make('heading_line1')->label('Heading Line 1'),
                        TextInput::make('heading_line2')->label('Heading Line 2 (Accent)'),
                    ]),
                Section::make('Story Panel')
                    ->schema([
                        TextInput::make('story_label')->label('Story Label'),
                        TextInput::make('story_heading')->label('Story Heading'),
                        RichEditor::make('story_body')->label('Story Body')->columnSpanFull(),
                    ]),
                Section::make('Globe')
                    ->schema([
                        TextInput::make('hint')->label('Globe Hint Text'),
                        TextInput::make('canvas_aria')->label('Canvas Aria Label')
                            ->helperText('Accessibility label for the globe. Country count is appended automatically.'),
                    ]),
            ]);
    }

    public function save(): void
    {
        try {
            $this->saveSettingsGroup(
                GlobalAccessPointsSettings::class,
                $this->form->getState()
            );

            Notification::make()
                ->title('Global Access Points saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Global Access Points')
                ->danger()
                ->send();
        }
    }
}
