<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryPostResource\Pages;
use App\Filament\Resources\CategoryPostResource\RelationManagers;
use App\Models\CategoryPost;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
            ->columns([
                TextColumn::make('name')->label('Categoría'),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/F/Y, h:m a'),
                TextColumn::make('updated_at')
                    ->label('Creado el')
                    ->dateTime('d/F/Y, h:m a'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
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
