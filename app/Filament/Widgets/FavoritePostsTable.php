<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FavoritePostsTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()->where("favorite", "=", true)
            )
            ->reorderable('position')
            ->striped()
            ->heading("Publicaciones en favoritos")
            ->columns([
                TextColumn::make("name")
                    ->label("Publicación")
                    ->wrap()
                    ->description(fn($record)=>$record->title),
            ])
            ->actions([
                Action::make("view")
                    ->label("Abrir")
                    ->icon("heroicon-o-arrow-right")
                    ->iconPosition(IconPosition::After)
                    ->url(fn(Post $record):string => route("filament.admin.resources.posts.edit", $record))
            ])
            ->emptyStateIcon("heroicon-o-newspaper")
            ->emptyStateDescription("Agrega publicaciones a favoritos desde la sección de publicaciones.")
            ->emptyStateHeading("No hay publicaciones");
    }
}
