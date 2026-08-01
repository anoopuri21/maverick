<?php

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Resources\OurStoryTestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOurStoryTestimonials extends ListRecords
{
    protected static string $resource = OurStoryTestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
