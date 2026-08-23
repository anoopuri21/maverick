<?php

namespace App\Livewire;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource;
use App\Models\OurStoryTestimonial;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
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
        return OurStoryTestimonialResource::table(
            $table
                ->query(OurStoryTestimonial::query())
                ->headerActions([
                    CreateAction::make()
                        ->form(fn (Form $form) => OurStoryTestimonialResource::form($form))
                        ->mutateFormDataUsing(fn (array $data): array => MediaPicker::syncUrlFromAsset($data, 'photo')),
                ])
                ->actions([
                    EditAction::make()
                        ->form(fn (Form $form) => OurStoryTestimonialResource::form($form))
                        ->mutateFormDataUsing(function (array $data) {
                            $data = MediaPicker::syncUrlFromAsset($data, 'photo');
                            $record = $this->getMountedTableActionRecord();
                            if (empty($data['photo']) && $record && filled($record->photo)) {
                                $data['photo'] = $record->photo;
                            }

                            return $data;
                        }),
                    DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
