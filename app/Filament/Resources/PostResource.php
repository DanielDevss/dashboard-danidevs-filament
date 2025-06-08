<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

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
                            Fieldset::make("Metas")
                                ->columns(2)
                                ->schema([
                                    TextInput::make("title")
                                        ->label("Titulo de la publicación")
                                        ->placeholder("Ingresa un titulo a esta publicación")
                                        ->required()
                                        ->unique('posts', 'title', ignoreRecord: true),
                                    TextInput::make("description")
                                        ->label("Descripción para SEO")
                                        ->placeholder("Ingresa un descripción de no más de 170 carácteres")
                                        ->required()
                                        ->maxLength(170),
                                ]),
                            Fieldset::make("Información")
                                ->schema([
                                    Select::make('categories')
                                        ->label('Categorias relacionadas')
                                        ->placeholder('Selecciona una o más categorías')
                                        ->relationship('categories', 'name')
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->createOptionForm([
                                            TextInput::make("name")
                                                ->label("Nombre de la categoría")
                                                ->placeholder("Ingresa un nombre para la categoría")
                                                ->maxLength(125)
                                                ->required(),
                                            TextInput::make("description")
                                                ->label("Descripción")
                                                ->placeholder("Máximo 170 carácteres")
                                                ->maxLength(170)
                                                ->required(),
                                        ]),
                                    Select::make('technologies')
                                        ->label('Tecnologias relacionadas')
                                        ->placeholder('Selecciona una o más tecnologias')
                                        ->relationship('technologies', 'name')
                                        ->preload()
                                        ->multiple()
                                        ->searchable()
                                        ->createOptionForm([
                                            TextInput::make("name")
                                                ->label("Nombre de la tecnología")
                                                ->placeholder("Ingresa un nombre para la tecnología")
                                                ->maxLength(125)
                                                ->required(),
                                            FileUpload::make("brand")
                                                ->label("Logotipo")
                                                ->placeholder("Sube un logo de la tecnología")
                                                ->required()
                                                ->image()
                                        ]),
                                    TextInput::make("name")
                                        ->label("Alias")
                                        ->placeholder("Ingresa un alias a tu publicación")
                                        ->required()
                                        ->unique('posts', 'name', ignoreRecord: true)
                                        ->columnSpan('full'),
                                ]),
                            Fieldset::make("Visibilidad")
                                ->columns(2)
                                ->schema([
                                    Toggle::make("public")
                                        ->label("Mantener público")
                                        ->onIcon("heroicon-o-globe-americas")
                                        ->offIcon("heroicon-o-link-slash"),
                                    Toggle::make("favorite")
                                        ->label("Públicación en favoritos")
                                        ->onIcon("heroicon-s-star")
                                        ->offIcon("heroicon-o-star"),
                                ]),
                        ]),

                    Step::make("Media")
                        ->icon('heroicon-o-photo')
                        ->columns(2)
                        ->schema([
                            FileUpload::make("thumb")
                                ->label("Miniatura de la publicación")
                                ->disk("public")
                                ->directory("posts/thumbs")
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatio('4:3')
                                ->imageCropAspectRatio('4:3')
                                ->imageResizeTargetWidth('560')
                                ->maxSize(1024)
                                ->placeholder("Selecciona una miniatura para la publicación")
                                ->required(),
                            FileUpload::make("banner")
                                ->label("Banner de la publicación")
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios(['31:9', '21:9', '16:9', null])
                                ->imageResizeTargetWidth('1280')
                                ->imageCropAspectRatio('31:9')
                                ->disk("public")
                                ->directory("posts/banner")
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
                                ->fileAttachmentsDirectory('posts/attachments')
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
            ->striped()
            ->defaultSort("created_at", "desc")
            ->reorderable('position')
            ->groups([
                Group::make("categories.name")->label("Categorías")->collapsible(),
                Group::make("technologies.name")->label("Tecnologías")->collapsible(),
                Group::make("created_at")->label("Fecha de creación")->date()->collapsible(),
                Group::make("updated_at")->label("Fecha de actualización")->date()->collapsible(),
            ])
            ->columns([
                ImageColumn::make("thumb")
                    ->circular()
                    ->simpleLightbox()
                    ->toggleable(),
                ImageColumn::make("banner")
                    ->circular()
                    ->simpleLightbox()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("name")
                    ->label("Alias")
                    ->searchable()
                    ->description(fn(Post $record): string => $record->title)
                    ->wrap()
                    ->toggleable()
                    ->weight("bold"),
                TextColumn::make("categories.name")
                    ->label("Categorías")
                    ->searchable()
                    ->placeholder("Agregue categorías")
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("technologies.name")
                    ->label("Tecnologías")
                    ->searchable()
                    ->placeholder("Agregue tecnologías")
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make("public")
                    ->label("Visible")
                    ->toggleable(),
                ToggleColumn::make("favorite")
                    ->label("Favorito")
                    ->toggleable(),
                TextColumn::make("created_at")
                    ->label("Creado el")
                    ->toggleable()
                    ->dateTime("d/F/Y, h:m a")
                    ->sortable(),
                TextColumn::make("updated_at")
                    ->label("Actualizado el")
                    ->dateTime("d/F/Y, h:m a")
                    ->toggleable()
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make("categories")
                    ->label('Categorias relacionadas')
                    ->placeholder('Categorias')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                SelectFilter::make("technologies")
                    ->label('Tecnologías relacionadas')
                    ->placeholder('Tecnologías')
                    ->relationship('technologies', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                TernaryFilter::make('favorite')->label("Mostrar favoritos")->placeholder("Mostrar favoritos y no favoritos"),
                TernaryFilter::make('public')->label("Visibilidad")->trueLabel("Público")->falseLabel("Borrador")->placeholder("Mostrar ambos")
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
