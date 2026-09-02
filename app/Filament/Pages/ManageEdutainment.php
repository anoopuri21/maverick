<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HydratesRepeaterMediaFields;
use App\Filament\Concerns\SavesSettingsGroups;
use App\Filament\Forms\Components\MediaPicker;
use App\Settings\EdutainmentExperiencesSettings;
use App\Settings\EdutainmentFaqSettings;
use App\Settings\EdutainmentFinalCtaSettings;
use App\Settings\EdutainmentHeroSettings;
use App\Settings\EdutainmentInstitutionsSettings;
use App\Settings\EdutainmentIntroSettings;
use App\Settings\EdutainmentLearningBeyondSettings;
use App\Settings\EdutainmentPackagesSettings;
use App\Settings\EdutainmentProgrammesSettings;
use App\Settings\EdutainmentSeoSettings;
use App\Settings\EdutainmentThemesSettings;
use App\Settings\EdutainmentWhatIsSettings;
use App\Settings\EdutainmentWhoForSettings;
use App\Settings\EdutainmentWhyChooseSettings;
use App\Support\EdutainmentIcons;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;

class ManageEdutainment extends Page implements HasForms
{
    use HydratesRepeaterMediaFields;
    use SavesSettingsGroups;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Edutainment Page';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.pages.manage-edutainment';

    public array $data = [];

    public function mount(): void
    {
        $whatIs = app(EdutainmentWhatIsSettings::class)->toArray();
        $whatIs['items'] = $this->wrapStringList($whatIs['items'] ?? []);

        $learning = app(EdutainmentLearningBeyondSettings::class)->toArray();
        $learning['cards'] = array_values($learning['cards'] ?? []);

        $whoFor = app(EdutainmentWhoForSettings::class)->toArray();
        $whoFor['cards'] = array_values($whoFor['cards'] ?? []);
        $whoFor['ctas'] = array_values($whoFor['ctas'] ?? []);

        $intro = app(EdutainmentIntroSettings::class)->toArray();
        $intro['ctas'] = array_values($intro['ctas'] ?? []);

        $programmes = app(EdutainmentProgrammesSettings::class)->toArray();
        $programmes['cards'] = array_values(array_map(function ($card) {
            $card['bullets'] = $this->wrapStringList($card['bullets'] ?? []);

            return $card;
        }, $programmes['cards'] ?? []));
        $programmes['cards'] = $this->hydrateRepeaterMediaFields($programmes['cards'], 'image');
        $programmes['china_items'] = array_values($programmes['china_items'] ?? []);

        $themes = app(EdutainmentThemesSettings::class)->toArray();
        $themes['cards'] = array_values($themes['cards'] ?? []);

        $experiences = app(EdutainmentExperiencesSettings::class)->toArray();
        $experiences['categories'] = array_values(array_map(function ($category) {
            $category['items'] = $this->wrapStringList($category['items'] ?? []);

            return $category;
        }, $experiences['categories'] ?? []));

        $whyChoose = app(EdutainmentWhyChooseSettings::class)->toArray();
        $whyChoose['cards'] = array_values($whyChoose['cards'] ?? []);

        $packages = app(EdutainmentPackagesSettings::class)->toArray();
        $packages['items'] = $this->wrapStringList($packages['items'] ?? []);
        $packages['ctas'] = array_values($packages['ctas'] ?? []);

        $institutions = app(EdutainmentInstitutionsSettings::class)->toArray();
        $institutions['tiles'] = array_values($institutions['tiles'] ?? []);
        $institutions['ctas'] = array_values($institutions['ctas'] ?? []);

        $faq = app(EdutainmentFaqSettings::class)->toArray();
        $faq['items'] = array_values($faq['items'] ?? []);

        $finalCta = app(EdutainmentFinalCtaSettings::class)->toArray();
        $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);

        $this->form->fill([
            'hero' => app(EdutainmentHeroSettings::class)->toArray(),
            'intro' => $intro,
            'whatIs' => $whatIs,
            'learning' => $learning,
            'whoFor' => $whoFor,
            'programmes' => $programmes,
            'themes' => $themes,
            'experiences' => $experiences,
            'whyChoose' => $whyChoose,
            'packages' => $packages,
            'institutions' => $institutions,
            'faq' => $faq,
            'finalCta' => $finalCta,
            'seo' => app(EdutainmentSeoSettings::class)->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Edutainment')
                    ->tabs([
                        Tab::make('Hero')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('hero.tag')->label('Eyebrow Tag'),
                                TextInput::make('hero.heading')->label('Heading'),
                                TextInput::make('hero.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('hero.description')->label('Description')->columnSpanFull(),
                                TextInput::make('hero.background_image')->hidden(),
                                MediaPicker::forField('hero.background_image', 'edutainment/hero')
                    ->label('Background Image')
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Intro')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                TextInput::make('intro.label')->label('Section Label'),
                                TextInput::make('intro.title_line1')->label('Title Line 1'),
                                Grid::make(2)->schema([
                                    TextInput::make('intro.title_line2')->label('Title Line 2'),
                                    TextInput::make('intro.title_line2_italic')->label('Title Line 2 (Italic)'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('intro.title_line3')->label('Title Line 3'),
                                    TextInput::make('intro.title_line3_italic')->label('Title Line 3 (Italic)'),
                                ]),
                                RichEditor::make('intro.body')->label('Body')->columnSpanFull(),
                                TextInput::make('intro.emphasis')->label('Emphasis Line')->columnSpanFull(),
                                $this->ctaRepeater('intro.ctas'),
                            ]),

                        Tab::make('What Is')
                            ->icon('heroicon-o-light-bulb')
                            ->schema([
                                ...$this->sectionHeaderFields('whatIs'),
                                Grid::make(3)->schema([
                                    TextInput::make('whatIs.wordmark_line1')->label('Wordmark Line 1'),
                                    TextInput::make('whatIs.wordmark_plus')->label('Wordmark Plus'),
                                    TextInput::make('whatIs.wordmark_line2')->label('Wordmark Line 2'),
                                ]),
                                TextInput::make('whatIs.wordmark_sub')->label('Wordmark Subtitle')->columnSpanFull(),
                                RichEditor::make('whatIs.lead')->label('Lead')->columnSpanFull(),
                                RichEditor::make('whatIs.body')->label('Body')->columnSpanFull(),
                                TextInput::make('whatIs.list_title')->label('List Title')->columnSpanFull(),
                                $this->stringListRepeater('whatIs.items', 'Programme Combinations'),
                                RichEditor::make('whatIs.quote')->label('Quote')->columnSpanFull(),
                            ]),

                        Tab::make('Learning Beyond')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                ...$this->sectionHeaderFields('learning'),
                                RichEditor::make('learning.body')->label('Body')->columnSpanFull(),
                                TextInput::make('learning.image')->hidden(),
                                MediaPicker::forField('learning.image', 'edutainment/learning-beyond')
                    ->label('Section Image')
                    ->columnSpanFull(),
                                TextInput::make('learning.cards_heading')->label('Cards Heading')->columnSpanFull(),
                                Repeater::make('learning.cards')
                                    ->label('Outcome Cards')
                                    ->schema([
                                        TextInput::make('icon')->label('Emoji / Icon'),
                                        TextInput::make('title'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Who For')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                ...$this->sectionHeaderFields('whoFor'),
                                RichEditor::make('whoFor.intro')->label('Intro')->columnSpanFull(),
                                Repeater::make('whoFor.cards')
                                    ->label('Audience Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                $this->ctaRepeater('whoFor.ctas'),
                            ]),

                        Tab::make('Programmes')
                            ->icon('heroicon-o-map')
                            ->schema([
                                ...$this->sectionHeaderFields('programmes'),
                                Repeater::make('programmes.cards')
                                    ->label('Programme Cards')
                                    ->schema([
                                        TextInput::make('image')->hidden(),
                                        MediaPicker::forField('image', 'edutainment/programmes')
                    ->label('Card Image')
                    ->columnSpanFull(),
                                        Grid::make(2)->schema([
                                            TextInput::make('badge')->label('Badge'),
                                            Toggle::make('is_featured')->label('Featured Card'),
                                        ]),
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                        $this->stringListRepeater('bullets', 'Bullet Points'),
                                        Grid::make(2)->schema([
                                            TextInput::make('cta_label')->label('CTA Label'),
                                            TextInput::make('cta_url')->label('CTA URL'),
                                        ]),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                Repeater::make('programmes.china_items')
                                    ->label('Featured Experience Items')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                Grid::make(2)->schema([
                                    TextInput::make('programmes.china_cta_label')->label('Featured Block CTA Label'),
                                    TextInput::make('programmes.china_cta_url')->label('Featured Block CTA URL'),
                                ]),
                            ]),

                        Tab::make('Themes')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                ...$this->sectionHeaderFields('themes'),
                                RichEditor::make('themes.intro')->label('Intro')->columnSpanFull(),
                                Repeater::make('themes.cards')
                                    ->label('Theme Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Experiences')
                            ->icon('heroicon-o-star')
                            ->schema([
                                ...$this->sectionHeaderFields('experiences'),
                                RichEditor::make('experiences.intro')->label('Intro')->columnSpanFull(),
                                Repeater::make('experiences.categories')
                                    ->label('Experience Categories')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        $this->stringListRepeater('items', 'List Items'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                                TextInput::make('experiences.note')->label('Closing Note')->columnSpanFull(),
                            ]),

                        Tab::make('Why Choose')
                            ->icon('heroicon-o-heart')
                            ->schema([
                                ...$this->sectionHeaderFields('whyChoose'),
                                Repeater::make('whyChoose.cards')
                                    ->label('Value Cards')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('title'),
                                        RichEditor::make('description')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Packages')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                ...$this->sectionHeaderFields('packages'),
                                RichEditor::make('packages.intro')->label('Intro')->columnSpanFull(),
                                $this->stringListRepeater('packages.items', 'Inclusions'),
                                TextInput::make('packages.note')->label('Note')->columnSpanFull(),
                                $this->ctaRepeater('packages.ctas'),
                            ]),

                        Tab::make('Institutions')
                            ->icon('heroicon-o-building-library')
                            ->schema([
                                ...$this->sectionHeaderFields('institutions'),
                                RichEditor::make('institutions.intro')->label('Intro')->columnSpanFull(),
                                Repeater::make('institutions.tiles')
                                    ->label('Programme Type Tiles')
                                    ->schema([
                                        $this->iconSelect(),
                                        TextInput::make('label'),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
                                TextInput::make('institutions.note')->label('Note')->columnSpanFull(),
                                $this->ctaRepeater('institutions.ctas'),
                            ]),

                        Tab::make('FAQ')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([
                                ...$this->sectionHeaderFields('faq'),
                                Repeater::make('faq.items')
                                    ->label('Questions')
                                    ->schema([
                                        TextInput::make('question')->columnSpanFull(),
                                        RichEditor::make('answer')->columnSpanFull(),
                                    ])
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                    ->columnSpanFull(),
                            ]),

                        Tab::make('Final CTA')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                TextInput::make('finalCta.heading')->label('Heading'),
                                TextInput::make('finalCta.heading_italic')->label('Heading (Italic)'),
                                RichEditor::make('finalCta.body')->label('Body')->columnSpanFull(),
                                TextInput::make('finalCta.emphasis')->label('Emphasis Line')->columnSpanFull(),
                                TextInput::make('finalCta.background_image')->hidden(),
                                MediaPicker::forField('finalCta.background_image', 'edutainment/cta')
                    ->label('Background Image')
                    ->columnSpanFull(),
                                $this->ctaRepeater('finalCta.ctas'),
                                Grid::make(2)->schema([
                                    TextInput::make('finalCta.whatsapp_label')->label('WhatsApp Button Label'),
                                    Toggle::make('finalCta.show_whatsapp')->label('Show WhatsApp Button')->inline(false),
                                ]),
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
                                TextInput::make('seo.og_image_url')->hidden(),
                                MediaPicker::forField('seo.og_image_url', 'edutainment/seo')->label('OG Image'),
                                Grid::make(2)->schema([
                                    Select::make('seo.og_type')->label('OG Type')
                                        ->options(['website' => 'Website', 'article' => 'Article', 'profile' => 'Profile']),
                                    Select::make('seo.twitter_card')->label('Twitter Card')
                                        ->options(['summary' => 'Summary', 'summary_large_image' => 'Summary Large Image']),
                                ]),
                                TextInput::make('seo.twitter_title')->label('Twitter Title')->maxLength(70),
                                Textarea::make('seo.twitter_description')->label('Twitter Description')->rows(3)->maxLength(200),
                                TextInput::make('seo.twitter_image_url')->hidden(),
                                MediaPicker::forField('seo.twitter_image_url', 'edutainment/seo')->label('Twitter Image'),
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

            $hero = $this->syncImageIfSelected($data['hero'] ?? [], 'background_image');

            $intro = $data['intro'] ?? [];
            $intro['ctas'] = array_values($intro['ctas'] ?? []);

            $whatIs = $data['whatIs'] ?? [];
            $whatIs['items'] = $this->normalizeStringList($whatIs['items'] ?? []);

            $learning = $this->syncImageIfSelected($data['learning'] ?? [], 'image');
            $learning['cards'] = array_values($learning['cards'] ?? []);

            $whoFor = $data['whoFor'] ?? [];
            $whoFor['cards'] = array_values($whoFor['cards'] ?? []);
            $whoFor['ctas'] = array_values($whoFor['ctas'] ?? []);

            $existingProgrammes = app(EdutainmentProgrammesSettings::class)->toArray();

            $programmes = $data['programmes'] ?? [];
            $programmes['cards'] = $this->hydrateRepeaterMediaFields($programmes['cards'] ?? [], 'image');
            foreach ($programmes['cards'] ?? [] as &$card) {
                $card = $this->syncImageIfSelected($card, 'image');
                $card['bullets'] = $this->normalizeStringList($card['bullets'] ?? []);
                $card['is_featured'] = (bool) ($card['is_featured'] ?? false);
            }
            unset($card);
            $programmes['cards'] = $this->preserveRepeaterImageFields(
                array_values($programmes['cards'] ?? []),
                $existingProgrammes['cards'] ?? [],
                'image'
            );
            $programmes['china_items'] = array_values($programmes['china_items'] ?? []);

            $themes = $data['themes'] ?? [];
            $themes['cards'] = array_values($themes['cards'] ?? []);

            $experiences = $data['experiences'] ?? [];
            foreach ($experiences['categories'] ?? [] as &$category) {
                $category['items'] = $this->normalizeStringList($category['items'] ?? []);
            }
            unset($category);
            $experiences['categories'] = array_values($experiences['categories'] ?? []);

            $whyChoose = $data['whyChoose'] ?? [];
            $whyChoose['cards'] = array_values($whyChoose['cards'] ?? []);

            $packages = $data['packages'] ?? [];
            $packages['items'] = $this->normalizeStringList($packages['items'] ?? []);
            $packages['ctas'] = array_values($packages['ctas'] ?? []);

            $institutions = $data['institutions'] ?? [];
            $institutions['tiles'] = array_values($institutions['tiles'] ?? []);
            $institutions['ctas'] = array_values($institutions['ctas'] ?? []);

            $faq = $data['faq'] ?? [];
            $faq['items'] = array_values($faq['items'] ?? []);

            $finalCta = $this->syncImageIfSelected($data['finalCta'] ?? [], 'background_image');
            $finalCta['ctas'] = array_values($finalCta['ctas'] ?? []);
            $finalCta['show_whatsapp'] = (bool) ($finalCta['show_whatsapp'] ?? false);

            $seo = $data['seo'] ?? [];
            $seo = $this->syncImageIfSelected($seo, 'og_image_url');
            $seo = $this->syncImageIfSelected($seo, 'twitter_image_url');

            $this->saveSettingsGroup(EdutainmentHeroSettings::class, $hero);
            $this->saveSettingsGroup(EdutainmentIntroSettings::class, $intro);
            $this->saveSettingsGroup(EdutainmentWhatIsSettings::class, $whatIs);
            $this->saveSettingsGroup(EdutainmentLearningBeyondSettings::class, $learning);
            $this->saveSettingsGroup(EdutainmentWhoForSettings::class, $whoFor);
            $this->saveSettingsGroup(EdutainmentProgrammesSettings::class, $programmes);
            $this->saveSettingsGroup(EdutainmentThemesSettings::class, $themes);
            $this->saveSettingsGroup(EdutainmentExperiencesSettings::class, $experiences);
            $this->saveSettingsGroup(EdutainmentWhyChooseSettings::class, $whyChoose);
            $this->saveSettingsGroup(EdutainmentPackagesSettings::class, $packages);
            $this->saveSettingsGroup(EdutainmentInstitutionsSettings::class, $institutions);
            $this->saveSettingsGroup(EdutainmentFaqSettings::class, $faq);
            $this->saveSettingsGroup(EdutainmentFinalCtaSettings::class, $finalCta);
            $this->saveSettingsGroup(EdutainmentSeoSettings::class, $seo);

            Notification::make()
                ->title('Edutainment page saved')
                ->success()
                ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save Edutainment page')
                ->body('Please check required repeater fields and try again.')
                ->danger()
                ->send();
        }
    }


    protected function sectionHeaderFields(string $prefix): array
    {
        return [
            TextInput::make("{$prefix}.label")->label('Section Label'),
            TextInput::make("{$prefix}.title")->label('Title'),
            TextInput::make("{$prefix}.title_line2")->label('Title Line 2'),
            TextInput::make("{$prefix}.title_italic")->label('Title (Italic)'),
            Toggle::make("{$prefix}.title_break")->label('Line break before italic')->inline(false),
        ];
    }

    protected function ctaRepeater(string $name): Repeater
    {
        return Repeater::make($name)
            ->label('Buttons')
            ->schema([
                TextInput::make('label'),
                TextInput::make('url'),
                Select::make('style')
                    ->options([
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                        'outline' => 'Outline',
                    ])
                    ->default('primary'),
            ])
            ->reorderable()
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
            ->columnSpanFull();
    }

    protected function stringListRepeater(string $name, string $label): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->simple(TextInput::make('item'))
            ->reorderable()
            ->columnSpanFull();
    }

    protected function iconSelect(): Select
    {
        return Select::make('icon_key')
            ->label('Icon')
            ->options(EdutainmentIcons::options())
            ->searchable()
            ->nullable();
    }

    protected function wrapStringList(array $items): array
    {
        return array_values(array_map(
            fn ($item) => is_string($item) ? ['item' => $item] : $item,
            $items
        ));
    }

    protected function normalizeStringList(array $items): array
    {
        return array_values(array_filter(array_map(
            fn ($item) => is_array($item) ? ($item['item'] ?? null) : $item,
            $items
        )));
    }
}
