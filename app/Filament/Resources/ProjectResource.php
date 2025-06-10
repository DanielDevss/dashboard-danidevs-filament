<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = "proyecto";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make("Información")
                        ->description('Detalles básicos del proyecto')
                        ->columns(2)
                        ->icon("heroicon-o-queue-list")
                        ->schema([
                            Fieldset::make("Metas")
                                ->schema([
                                    TextInput::make("title")
                                        ->label("Titulo para el proyecto")
                                        ->placeholder("Ingresa un titulo al proyecto")
                                        ->unique('projects', 'title', ignoreRecord: true)
                                        ->maxLength(150)
                                        ->required(),
                                    TextInput::make("description")
                                        ->label("Descripción Meta")
                                        ->placeholder("Ingresa una descripción de maxímo 170 carácteres")
                                        ->maxLength(170)
                                        ->required(),
                                ]),
                            Fieldset::make("Información")
                                ->schema([
                                    Select::make("categories")
                                        ->label("Categorias relacionadas")
                                        ->placeholder("Selecciona una o más categorias.")
                                        ->relationship("categories", "name")
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->required()
                                        ->createOptionForm([
                                            TextInput::make("name")->label("Nombre de la categoría")->required()
                                        ]),
                                    Select::make("technologies")
                                        ->label("Tecnologías relacionadas")
                                        ->placeholder("Selecciona una o más tecnologías.")
                                        ->relationship("technologies", "name")
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->required(),
                                    TextInput::make("name")
                                        ->label("Alias del proyecto")
                                        ->placeholder("Ingresa un alias unico al proyecto")
                                        ->unique('projects', 'name', ignoreRecord: true)
                                        ->required()
                                        ->columnSpan('full'),
                                ]),
                            Fieldset::make("Visibilidad")
                                ->schema([
                                    Toggle::make("public")
                                        ->label("Mantener público")
                                        ->onIcon("heroicon-o-globe-americas")
                                        ->offIcon("heroicon-o-link-slash"),
                                    Toggle::make("favorite")
                                        ->label("Agregar a favoritos")
                                        ->onIcon("heroicon-s-star")
                                        ->offIcon("heroicon-o-star"),
                                ]),
                        ]),
                    Step::make("Media & Enlaces")
                        ->description('Imagenes, link del sitio, repositorio')
                        ->columns(2)
                        ->icon("heroicon-o-photo")
                        ->schema([
                            Fieldset::make('Imagenes')
                                ->schema([
                                    FileUpload::make("thumb")
                                        ->label("Miniatura")
                                        ->image()
                                        ->imageEditor()
                                        ->imageCropAspectRatio('4:3')
                                        ->imageResizeTargetWidth('560')
                                        ->disk("public")
                                        ->directory("projects/thumbs")
                                        ->visibility("public")
                                        ->openable()
                                        ->required(),
                                    FileUpload::make("banner")
                                        ->label("Portada del proyecto")
                                        ->image()
                                        ->imageEditor()
                                        ->imageCropAspectRatio('31:9')
                                        ->imageEditorAspectRatios([
                                            '31:9',
                                            '21:9',
                                            '16:9',
                                            null,
                                        ])
                                        ->imageResizeTargetWidth('1280')
                                        ->disk("public")
                                        ->directory("projects/banner")
                                        ->visibility("public")
                                        ->previewable()
                                        ->openable(),
                                        ]),
                            Fieldset::make('Enlaces')
                                ->schema([
                                    TextInput::make('link')
                                        ->label('Enlace del sitio')
                                        ->url()
                                        ->placeholder('Agrega el URL del sitio web')
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-link'),
                                    TextInput::make('repository')
                                        ->label('Repositorio')
                                        ->url()
                                        ->placeholder('Agrega el URL del repositorio del proyecto')
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-share')
                                ])
                        ]),
                    Step::make("Contenido")
                        ->description('Detalles y descripción')
                        ->icon("heroicon-o-newspaper")
                        ->schema([
                            RichEditor::make("content")
                                ->label("Contenido del proyecto")
                                ->placeholder("Explica el proyecto a detalle")
                                ->required()
                                ->fileAttachmentsDisk("public")
                                ->fileAttachmentsDirectory("projects/attachments")
                        ])
                ])->columnSpan("full")->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make("categories.name")->label("Categorías")->collapsible(),
                Group::make("technologies.name")->label("Tecnologías")->collapsible(),
                Group::make("created_at")->label("Fecha de creación")->date()->collapsible(),
                Group::make("updated_at")->label("Fecha de actualización")->date()->collapsible(),
            ])
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make("thumb")
                    ->circular()
                    ->simpleLightbox()
                    ->toggleable(),
                ImageColumn::make("banner")
                    ->circular()
                    ->simpleLightbox()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make("name")
                    ->label("Públicación")
                    ->searchable()
                    ->toggleable()
                    ->description(fn($record) => $record->title)
                    ->weight("bold")
                    ->wrap(),
                TextColumn::make("categories.name")
                    ->label("Categorias")
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
                    ->dateTime("d/F/Y")
                    ->toggleable()
                    ->sortable(),
                TextColumn::make("updated_at")
                    ->label("Editado el")
                    ->dateTime("d/F/Y")
                    ->toggleable()
                    ->sortable(),
            ])
            ->striped()
            ->filters([
                SelectFilter::make("categories")
                    ->label("Categoría")
                    ->relationship("categories", "name")
                    ->preload()
                    ->searchable()
                    ->multiple(),
                SelectFilter::make("technologies")
                    ->label("Tecnologías")
                    ->relationship("technologies", "name")
                    ->preload()
                    ->searchable()
                    ->multiple(),
                TernaryFilter::make("favorite")
                    ->label("Sección favorito")
                    ->boolean()
                    ->nullable()
                    ->placeholder("Mostrar favoritos y no favoritos")
                    ->trueLabel("Mostrar favoritos (inicio)")
                    ->falseLabel("En lista de proyectos"),
                TernaryFilter::make("public")
                    ->label("Visibilidad")
                    ->boolean()
                    ->nullable()
                    ->placeholder("Mostrar públicos y ocultos")
                    ->trueLabel("Mostrar públicos")
                    ->falseLabel("Mostrar ocultos")

            ], layout: Tables\Enums\FiltersLayout::Modal)
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
