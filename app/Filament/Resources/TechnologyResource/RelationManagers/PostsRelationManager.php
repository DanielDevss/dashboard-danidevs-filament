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

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';
    protected static ?string $pluralLabel = "publicaciones";
    protected static ?string $modelLabel = "publicación";
    protected static ?string $title = "Publicaciones";
    protected static ?string $icon = "heroicon-o-newspaper";

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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Publicación")
                    ->description(fn($record) => $record?->title)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label("Meta descripción")
                    ->searchable()
                    ->wrap()
                    ->lineClamp(2)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label("Categorias")
                    ->searchable()
                    ->wrap()
                    ->toggleable()
                    ->lineClamp(2),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Creado el")
                    ->dateTime("d/M/Y, h:m a")
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label("Editado el")
                    ->dateTime("d/M/Y, h:m a")
                    ->sortable()
                    ->toggleable(),
                    
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
