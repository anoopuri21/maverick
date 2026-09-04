<?php

namespace App\Filament\Pages\MbaMastersLanding\Concerns;

use App\Filament\Concerns\HydratesRepeaterMediaFields;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Throwable;

trait ManagesMbaMastersChunk
{
    use HydratesRepeaterMediaFields;
    public ?array $data = [];

    protected function richEditorToolbar(): array
    {
        return [
            'bold',
            'italic',
            'underline',
            'link',
            'bulletList',
            'orderedList',
            'redo',
            'undo',
        ];
    }

    protected function richEditor(string $name, string $label): RichEditor
    {
        return RichEditor::make($name)
            ->label($label)
            ->toolbarButtons($this->richEditorToolbar())
            ->columnSpanFull();
    }

    protected function chunkHint(string $text): Placeholder
    {
        return Placeholder::make('_chunk_hint')
            ->label('')
            ->content($text)
            ->columnSpanFull();
    }

    protected function getFormStateOrNotify(): ?array
    {
        try {
            return $this->form->getState();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);
            Notification::make()->title('Could not save')->danger()->send();

            return null;
        }
    }

    protected function notifySaved(string $chunk): void
    {
        Notification::make()
            ->title("MBA Masters — {$chunk} saved")
            ->success()
            ->send();
    }
}
