<?php

namespace App\Livewire;

use App\Filament\Resources\OurStoryTestimonialResource;
use App\Livewire\Concerns\MutatesEmbeddedMediaPicker;
use App\Support\TestimonialPages;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

class OurStoryTestimonialTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use MutatesEmbeddedMediaPicker;

    public function table(Table $table): Table
    {
        return OurStoryTestimonialResource::table(
            $table->query(OurStoryTestimonialResource::getEloquentQuery())
        )
            ->headerActions([
                CreateAction::make()
                    ->label('Add Testimonial')
                    ->form(fn (Form $form) => OurStoryTestimonialResource::form($form))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data = $this->mutateEmbeddedMedia($data, 'photo');
                        $data['pages'] = TestimonialPages::normalize($data['pages'] ?? []);

                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn (Form $form) => OurStoryTestimonialResource::form($form))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data = $this->mutateEmbeddedMedia($data, 'photo', $this->getMountedTableActionRecord());
                        $data['pages'] = TestimonialPages::normalize($data['pages'] ?? []);

                        return $data;
                    }),
                DeleteAction::make(),
            ]);
    }

    public function render(): View
    {
        return view('livewire.embedded-crud-table');
    }
}
