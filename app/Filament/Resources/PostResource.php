<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $modelLabel = 'publicación';
    protected static ?string $pluralLabel = 'publicaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make("Información")
                        ->icon('heroicon-o-queue-list')
                        ->columns(2)
                        ->schema([
                            Select::make('categories')
                                ->label('Categorias relacionadas')
                                ->placeholder('Selecciona una o más categorías')
                                ->relationship('categories', 'name')
                                ->preload()
                                ->searchable(),
                            TextInput::make("name")
                                ->label("Alias")
                                ->placeholder("Ingresa un alias a tu publicación")
                                ->required()
                                ->unique('posts', 'name', ignoreRecord: true),
                            TextInput::make("title")
                                ->label("Titulo de la publicación")
                                ->placeholder("Ingresa un titulo a esta publicación")
                                ->required()
                                ->unique('posts', 'title', ignoreRecord: true),
                            TextInput::make("description")
                                ->label("Descripción para SEO")
                                ->placeholder("Ingresa un descripción de no más de 170 carácteres")
                                ->columnSpan('full')
                                ->required()
                                ->maxLength(170),
                            ToggleButtons::make('public')
                                ->label("Estado de la publicación")
                                ->boolean(trueLabel: "Público", falseLabel: "Borrador")
                                ->icons([
                                    "1" => "heroicon-o-globe-asia-australia",
                                    "0" => "heroicon-o-link-slash"
                                ])
                                ->grouped()
                                ->required(),
                            ToggleButtons::make('favorite')
                                ->label("¿Agregar en favoritos?")
                                ->boolean()
                                ->grouped()
                                ->required(),
                        ]),

                    Step::make("Media")
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make("thumb")
                                ->label("Miniatura de la publicación")
                                ->image()
                                ->maxSize(1024)
                                ->placeholder("Selecciona una miniatura para la publicación")
                                ->required(),
                            FileUpload::make("banner")
                                ->label("Banner de la publicación")
                                ->image()
                                ->maxSize(2048)
                                ->placeholder("Selecciona un banner para la publicación")
                                ->required(),
                        ]),
                    Step::make("Contenido")
                        ->icon('heroicon-o-newspaper')
                        ->schema([
                            RichEditor::make("content")
                                ->label("Contenido de la publicación")
                                ->placeholder("Ingresa el contenido de la publicación")
                                ->fileAttachmentsDisk("public")
                                ->fileAttachmentsDirectory('attachments-post')
                                ->required()
                        ]),
                ])
                    ->columnSpan('full')
                    ->skippable(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label("Alias")
                    ->searchable(),
                ImageColumn::make("thumb")
                    ->label("Miniatura")
                    ->circular()
                    ->toggleable(),
                TextColumn::make("title")
                    ->label("Titulo")
                    ->searchable()
                    ->toggleable(),
                TextColumn::make("public")
                    ->label("Estado")
                    ->toggleable()
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Público' : 'Borrador')
                    ->toggleable()
                    ->icon(fn(bool $state): string => $state ? 'heroicon-o-globe-asia-australia' : 'heroicon-o-link-slash'),
                IconColumn::make("favorite")
                    ->boolean()
                    ->trueColor("success")
                    ->falseColor("gray")
                    ->trueIcon("heroicon-o-check-circle")
                    ->falseIcon("heroicon-o-x-circle")
                    ->toggleable()
                    ->label("Favorito"),
                TextColumn::make("created_at")
                    ->label("Creado el")
                    ->toggleable()
                    ->dateTime("d/F/Y, h:m a"),
                TextColumn::make("updated_at")
                    ->label("Actualizado el")
                    ->dateTime("d/F/Y, h:m a")
                    ->toggleable()
            ])
            ->filters([
                SelectFilter::make("categories")
                    ->label('Categorias relacionadas')
                    ->placeholder('Categorias')
                    ->relationship('categories', 'name')
                    ->preload()
                    ->searchable(),
                TernaryFilter::make('favorite')->label("Favoritos"),
                TernaryFilter::make('public')->label("Publicados")
            ], layout: FiltersLayout::Modal)
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
