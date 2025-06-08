<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResource\Pages;
use App\Filament\Resources\WorkResource\RelationManagers;
use App\Models\Work;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?string $navigationLabel = "Experiencias de trabajo";
    protected static ?string $modelLabel = "experiencias de trabajo";
    protected static ?string $title = "Experiencias de trabajo";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("company")
                    ->label("Compañia")
                    ->placeholder("Nombre de la empresa")
                    ->required(),
                Select::make("workstation")
                    ->label("Puesto desempeñado")
                    ->placeholder("Selecciona un puesto")
                    ->options([
                        "Front-End" => "Front-End",
                        "Back-End" =>  "Back-End",
                        "Full-Stack" => "Full-Stack",
                        "Desarrollador web" => "Desarrollador web"
                    ])
                    ->required(),
                DatePicker::make("init")
                    ->label("Fecha de inicio")
                    ->placeholder("Ingresa la fecha de inicio")
                    ->required()
                    ->format('Y-m-d'),
                DatePicker::make("finish")
                    ->label("Fecha de salida")
                    ->placeholder("Ingresa la fecha de salida")
                    ->format('Y-m-d')
                    ->nullable(),
                RichEditor::make("content")
                    ->label("Experiencia")
                    ->placeholder("Ingresa la experiencia vivida y actividades realizadas...")
                    ->nullable()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("company")->label("Compañia")->weight("bold"),
                TextColumn::make("workstation")->label("Cargo"),
                TextColumn::make("init")->label("Inicio")->date('F \de Y')->sortable(),
                TextColumn::make("finish")->label("Fin")->date('F \de Y')->sortable()->placeholder("Actual"),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
