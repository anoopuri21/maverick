<?php

namespace App\Livewire;

use App\Filament\Resources\OurStoryTestimonialResource;
use App\Models\OurStoryTestimonial;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Testimonial CRUD table for the Our Story page.
 * Reuses OurStoryTestimonialResource::form() and ::table().
 */
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
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => OurStoryTestimonialResource::form($form)),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => OurStoryTestimonialResource::form($form)),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
