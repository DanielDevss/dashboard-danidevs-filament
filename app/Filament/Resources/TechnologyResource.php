<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnologyResource\Pages;
use App\Filament\Resources\TechnologyResource\RelationManagers;
use App\Models\Technology;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnologyResource extends Resource
{
    protected static ?string $model = Technology::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'Catálogos';

    protected static ?string $pluralLabel = 'Tecnologías';
    protected static ?string $modelLabel = 'Tecnología';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre de la tecnología')
                    ->placeholder('Ingresa el nombre de la tecnología')
                    ->unique('technologies', 'name')
                    ->required(),
                FileUpload::make('brand')
                    ->label('Logotipo')
                    ->placeholder('Sube el logo o icono de la tecnología')
                    ->required()
                    ->maxSize(1024)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('brand')
                    ->label("Logotipo/Isotipo")
                    ->circular(),
                TextColumn::make('name')->label('Tecnología'),
                TextColumn::make('created_at')->label('Creado el')->dateTime('d/F/Y, h:m a'),
                TextColumn::make('updated_at')->label('Creado el')->dateTime('d/F/Y, h:m a'),
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
            'index' => Pages\ListTechnologies::route('/'),
            'create' => Pages\CreateTechnology::route('/create'),
            'edit' => Pages\EditTechnology::route('/{record}/edit'),
        ];
    }
}
