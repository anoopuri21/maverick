<?php

namespace App\Filament\Support;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\SeoFormFields;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

abstract class ManagesPageSeo extends SettingsPage
{
    use HandlesCloudinaryImageFields;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?int $navigationSort = 99;

    protected static string $mediaFolder = 'seo';

    protected static string $pageLabel = 'this page';

    public function form(Form $form): Form
    {
        return $form->schema(SeoFormFields::forSettingsPage(static::$mediaFolder, static::$pageLabel));
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['og_image_url_input'] = $data['og_image_url'] ?? null;
        $data['twitter_image_url_input'] = $data['twitter_image_url'] ?? null;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = SeoFormFields::syncSettingsImages($data);

        return $this->preserveExistingImageFields($data, app(static::getSettings()));
    }
}
