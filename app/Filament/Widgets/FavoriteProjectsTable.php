<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FavoriteProjectsTable extends BaseWidget
{
    protected static ?string $title = "Proyectos en favoritos";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()->where("favorite", "=", true)
            )
            ->reorderable('position')
            ->striped()
            ->heading("Proyectos en favoritos")
            ->columns([
                TextColumn::make("name")
                    ->label("Proyecto")
                    ->wrap()
                    ->description(fn($record)=> $record->title),
            ])
            ->actions([
                Action::make("view")
                    ->label("Abrir")
                    ->icon("heroicon-o-arrow-right")
                    ->iconPosition(IconPosition::After)
                    ->url(fn(Project $record):string => route("filament.admin.resources.projects.edit", $record))
            ])
            ->emptyStateIcon("heroicon-o-code-bracket")
            ->emptyStateHeading("No hay proyectos")
            ->emptyStateDescription("Agrega proyectos a favoritos desde la sección de proyectos");
    }
}
