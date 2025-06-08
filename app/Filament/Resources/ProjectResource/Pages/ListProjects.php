<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'Todos' =>  Tab::make()
                ->query(fn (Builder $query) => $query)
                ->icon('heroicon-o-queue-list')
                ->badge(Project::count())
                ->badgeColor('info'),
            'Público' =>  Tab::make()
                ->query(fn (Builder $query) => $query->where('public', true))
                ->icon('heroicon-o-signal')
                ->badge(fn () => Project::where('public', true)->count())
                ->badgeColor('primary'),
            'Ocultos' =>  Tab::make()
                ->query(fn (Builder $query) => $query->where('public', false))
                ->icon('heroicon-o-signal-slash')
                ->badge(fn () => Project::where('public', false)->count())
                ->badgeColor('gray'),
            'Favoritos' =>  Tab::make()
                ->query(fn (Builder $query) => $query->where('favorite', true))
                ->icon('heroicon-o-star')
                ->badge(fn () => Project::where('favorite', true)->count())
                ->badgeColor('warning')
        ];
    }
}
