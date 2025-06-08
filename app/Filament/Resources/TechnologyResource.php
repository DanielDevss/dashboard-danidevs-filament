<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnologyResource\Pages;
use App\Filament\Resources\TechnologyResource\RelationManagers;
use App\Filament\Resources\TechnologyResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\TechnologyResource\RelationManagers\ProjectsRelationManager;
use App\Models\Technology;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

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
                    ->unique('technologies', 'name', ignoreRecord: true)
                    ->required(),
                FileUpload::make('brand')
                    ->label('Logotipo')
                    ->disk("public")
                    ->directory("technologies")
                    ->placeholder('Sube el logo o icono de la tecnología')
                    ->required()
                    ->maxSize(1024)
                    ->deleteUploadedFileUsing(function ($state, $record) {
                        if ($record && $record->brand) {
                            Storage::disk('public')->delete($record->brand);
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('brand')
                    ->label("")
                    ->circular()
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('name')->label('Tecnología')->weight('bold'),
                TextColumn::make('projects_count')->label("Proyectos")->counts('projects')->alignRight()->sortable()->toggleable(),
                TextColumn::make('posts_count')->label("Publicaciones")->counts('posts')->alignRight()->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Creado el')->dateTime('d/F/Y, h:m a')->sortable()->toggleable(),
                TextColumn::make('updated_at')->label('Actualizado el')->dateTime('d/F/Y, h:m a')->sortable()->toggleable(),
            ])
            ->extremePaginationLinks()
            ->poll('10m')
            ->striped()
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
            ProjectsRelationManager::class,
            PostsRelationManager::class
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
