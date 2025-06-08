<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs():array {
        return [
            'Todos' => Tab::make()
                ->query(fn (Builder $query) => $query)
                ->icon('heroicon-o-queue-list')
                ->badge(Post::count())
                ->badgeColor('info'),
            'Publicados' => Tab::make()
                ->query(fn (Builder $query) => $query->where('public', true))
                ->icon('heroicon-o-signal')
                ->badge(fn () => Post::where('public', true)->count())
                ->badgeColor('primary'),
            'Ocultos' => Tab::make()
                ->query(fn (Builder $query) => $query->where('public', false))
                ->icon('heroicon-o-signal-slash')
                ->badge(fn () => Post::where('public', false)->count())
                ->badgeColor('gray'),
            'Favoritos' => Tab::make()
                ->query(fn (Builder $query) => $query->where('favorite', true))
                ->icon('heroicon-o-star')
                ->badge(fn () => Post::where('favorite', false)->count())
                ->badgeColor('warning'),
        ];
    }
}
