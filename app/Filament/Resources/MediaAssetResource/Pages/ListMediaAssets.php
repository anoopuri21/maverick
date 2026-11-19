<?php

namespace App\Filament\Resources\MediaAssetResource\Pages;

use App\Filament\Resources\MediaAssetResource;
use App\Services\MediaCleanService;
use Filament\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListMediaAssets extends ListRecords
{
    protected static string $resource = MediaAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refreshUsage')
                ->label('Refresh usage')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function (): void {
                    $usage = app(\App\Services\MediaUsageService::class)->refresh();
                    $errors = $usage['errors'] ?? [];

                    if ($errors !== []) {
                        Notification::make()
                            ->title('Usage scan incomplete')
                            ->body('Usage flags were left unchanged: '.implode('; ', $errors))
                            ->warning()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Usage flags updated')
                        ->body("Used {$usage['used']} · unused {$usage['unused']} · duplicates {$usage['duplicates']}")
                        ->success()
                        ->send();
                }),
            Actions\Action::make('cleanMediaLibrary')
                ->label('Clean Media Library')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->modalHeading('Clean Media Library')
                ->modalDescription('Unused files only. Used media is never deleted. Soft-delete is reversible; Cloudinary destroy is optional.')
                ->modalSubmitActionLabel('Recycle unused media')
                ->form(function (): array {
                    $preview = app(MediaCleanService::class)->preview();
                    $unused = $preview['unused_assets'];
                    $lines = $unused === []
                        ? 'No unused media_assets found.'
                        : collect($unused)->take(40)->map(function (array $row) {
                            return '#'.$row['id'].'  '.($row['name'] ?: $row['public_id']).'  ['.($row['folder'] ?: '—').']';
                        })->implode("\n");

                    if (count($unused) > 40) {
                        $lines .= "\n… and ".(count($unused) - 40).' more';
                    }

                    $remote = $preview['cloudinary_error']
                        ? $preview['cloudinary_error']
                        : count($preview['cloudinary_orphans']).' Cloudinary file(s) not referenced by the site.';
                    $scanErrors = $preview['scan_errors'] ?? [];
                    $warning = $scanErrors !== []
                        ? '<p><strong>Reference scan incomplete; deletion will be refused:</strong> '.e(implode('; ', $scanErrors)).'</p>'
                        : '';

                    return [
                        Placeholder::make('summary')
                            ->content(new HtmlString(
                                '<p><strong>Used:</strong> '.$preview['usage']['used'].
                                ' &nbsp; <strong>Unused:</strong> '.$preview['usage']['unused'].
                                ' &nbsp; <strong>Duplicates:</strong> '.$preview['usage']['duplicates'].'</p>'.
                                '<p>'.$remote.'</p>'.$warning
                            )),
                        Textarea::make('preview')
                            ->label('Would recycle')
                            ->default($lines)
                            ->disabled()
                            ->rows(10),
                        Checkbox::make('confirm')
                            ->label('I understand this soft-deletes unused library rows only')
                            ->accepted(),
                        Checkbox::make('purge_cloudinary')
                            ->label('Also delete unused files from Cloudinary (public_id is kept in the recycle log)')
                            ->helperText('Leave unchecked to keep Cloudinary files. You can purge later with php artisan media:clean --confirm --purge-cloudinary.'),
                    ];
                })
                ->action(function (array $data): void {
                    if (empty($data['confirm'])) {
                        Notification::make()
                            ->title('Clean cancelled')
                            ->warning()
                            ->send();

                        return;
                    }

                    $result = app(MediaCleanService::class)->execute(
                        dryRun: false,
                        deleteFromCloudinary: (bool) ($data['purge_cloudinary'] ?? false),
                    );

                    if ($result['errors'] !== []) {
                        Notification::make()
                            ->title('Clean was incomplete')
                            ->body(implode('; ', $result['errors']))
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Unused media recycled')
                        ->body('Soft-deleted '.$result['deleted'].' row(s). Cloudinary destroyed: '.$result['cloudinary_deleted'].'.')
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
