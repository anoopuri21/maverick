<?php

namespace App\Filament\Resources\InsightResource\Pages;

use App\Filament\Resources\InsightResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListInsights extends ListRecords
{
    protected static string $resource = InsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'blogs' => Tab::make('Blogs')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereJsonContains('categories', 'blogs')),

            'news' => Tab::make('News')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereJsonContains('categories', 'news')),

            'events' => Tab::make('Events')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereJsonContains('categories', 'events'))
                ->badge('Coming Soon'),

            'student-success' => Tab::make('Student Success')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereJsonContains('categories', 'student-success'))
                ->badge('Coming Soon'),
        ];
    }
}
