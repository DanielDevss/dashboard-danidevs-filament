<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryPostResource\Pages;
use App\Filament\Resources\CategoryPostResource\RelationManagers;
use App\Filament\Resources\CategoryPostResource\RelationManagers\PostsRelationManager;
use App\Models\CategoryPost;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryPostResource extends Resource
{
    protected static ?string $model = CategoryPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';

    protected static ?string $navigationGroup = "Blog";

    protected static ?string $modelLabel = "categoria";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre de la categoría')
                    ->placeholder('Ingresa el nombre de la categoría')
                    ->unique('category_posts', 'name', ignoreRecord: true)
                    ->required(),
                TextInput::make('description')
                    ->label('Descripción')
                    ->placeholder('Ingresa una descripción para la categoría')
                    ->maxLength(170)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('name')
                    ->label('Categoría')
                    ->searchable()
                    ->wrap()
                    ->description(fn($record) => $record?->description
                        ? $record->description 
                        : "Sin descripción"
                    )
                    ->weight("bold"),
                TextColumn::make('posts_count')
                    ->label('Públicaciones')
                    ->searchable()
                    ->counts('posts')
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/F/Y, h:m a')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime('d/F/Y, h:m a')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategoryPosts::route('/'),
            'create' => Pages\CreateCategoryPost::route('/create'),
            'edit' => Pages\EditCategoryPost::route('/{record}/edit'),
        ];
    }
}
