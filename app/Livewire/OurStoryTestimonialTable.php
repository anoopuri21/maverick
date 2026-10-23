<?php

namespace App\Livewire;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource;
use App\Models\OurStoryTestimonial;
use App\Support\TestimonialPages;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class OurStoryTestimonialTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return OurStoryTestimonialResource::table($table)
            ->query(OurStoryTestimonial::query())
            ->headerActions([
                CreateAction::make()
                    ->label('Add Testimonial')
                    ->form(fn ($form) => OurStoryTestimonialResource::form($form))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data = MediaPicker::syncFieldFromAsset($data, 'photo', 'media_asset_id');
                        $data['pages'] = TestimonialPages::normalize($data['pages'] ?? []);

                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn ($form) => OurStoryTestimonialResource::form($form))
                    ->mutateFormDataUsing(function (array $data, OurStoryTestimonial $record): array {
                        $data = MediaPicker::syncFieldFromAsset($data, 'photo', 'media_asset_id');
                        $data = MediaPicker::preserveOrClearField($data, 'photo', $record->photo, 'media_asset_id');
                        $data['pages'] = TestimonialPages::normalize($data['pages'] ?? []);

                        return $data;
                    }),
                DeleteAction::make(),
            ]);
    }

    public function render()
    {
        return view('livewire.embedded-table');
    }
}
