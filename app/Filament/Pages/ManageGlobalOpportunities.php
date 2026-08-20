<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Settings\GlobalOpportunitiesSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGlobalOpportunities extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Global Opportunities';
    protected static ?int $navigationSort = 12;
    protected static string $settings = GlobalOpportunitiesSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Section Heading')->schema([
                TextInput::make('heading')->required()->columnSpanFull(),
                Textarea::make('subtitle')->rows(2)->columnSpanFull(),
            ]),

            Section::make('Left Column: Global Opportunities')->schema([
                TextInput::make('left_title')->label('Column Title')->required()->columnSpanFull(),
                Repeater::make('opportunities')
                    ->label('Opportunity Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('url')->label('Link URL'),
                        ]),
                        Textarea::make('desc')->label('Description')->rows(2)->columnSpanFull(),
                        Grid::make(2)->schema([
                            MediaPicker::forField('image_url', 'homepage/opportunities')
                                ->label('Image (Media Library)'),
                            TextInput::make('image_url_input')->label('Or Image URL')->url(),
                        ]),
                        Select::make('icon')->options(self::icons())->searchable()->default('sparkles'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]),

            Section::make('Right Column: Global Pathways')->schema([
                TextInput::make('right_title')->label('Column Title')->required()->columnSpanFull(),
                Repeater::make('pathways')
                    ->label('Pathway Items')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('url')->label('Link URL'),
                        ]),
                        Textarea::make('desc')->label('Description')->rows(2)->columnSpanFull(),
                        Grid::make(2)->schema([
                            MediaPicker::forField('image_url', 'homepage/pathways')
                                ->label('Image (Media Library)'),
                            TextInput::make('image_url_input')->label('Or Image URL')->url(),
                        ]),
                        Select::make('icon')->options(self::icons())->searchable()->default('sparkles'),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        foreach (['opportunities', 'pathways'] as $key) {
            foreach ($data[$key] ?? [] as &$item) {
                $item = MediaPicker::syncFieldFromAsset($item, 'image_url');
                if (empty($item['image_url']) && ! empty($item['image_url_input'])) {
                    $item['image_url'] = $item['image_url_input'];
                }
                unset($item['image_url_input']);
            }
            unset($item);
            $data[$key] = array_values($data[$key] ?? []);
        }
        return $data;
    }

    /** @return array<string,string> */
    protected static function icons(): array
    {
        return [
            'sparkles' => 'sparkles', 'globe' => 'globe', 'users' => 'users',
            'briefcase' => 'briefcase', 'trending-up' => 'trending-up', 'laptop' => 'laptop',
            'graduation-cap' => 'graduation-cap', 'layers' => 'layers', 'rocket' => 'rocket',
            'award' => 'award', 'compass' => 'compass', 'book-open' => 'book-open',
        ];
    }
}
