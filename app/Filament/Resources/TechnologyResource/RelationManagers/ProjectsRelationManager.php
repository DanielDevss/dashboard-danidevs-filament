<?php

namespace App\Filament\Resources\TechnologyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';
    protected static ?string $title = "Proyectos";
    protected static ?string $pluralLabel = "proyectos";
    protected static ?string $modelLabel = "proyecto";
    protected static ?string $icon = "heroicon-o-code-bracket";

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->striped()
            ->columns([
                Tables\Columns\ImageColumn::make('thumb')
                    ->label("")
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->weight("bold")
                    ->description(fn ($record) => $record?->title)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->lineClamp(2)
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Creado el")
                    ->toggleable()
                    ->dateTime("d/F/Y"),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label("Editado el")
                    ->toggleable()
                    ->dateTime("d/F/Y"),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
