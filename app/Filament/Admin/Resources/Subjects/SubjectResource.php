<?php

namespace App\Filament\Admin\Resources\Subjects;

use App\Filament\Admin\Resources\Subjects\Pages\CreateSubject;
use App\Filament\Admin\Resources\Subjects\Pages\EditSubject;
use App\Filament\Admin\Resources\Subjects\Pages\ListSubjects;
use App\Models\Level;
use App\Models\Subject;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Select::make('level_id')
                ->label('Level')
                ->options(Level::all()->pluck('name', 'id'))
                ->required(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            ColorPicker::make('color')->nullable(),
            TextInput::make('icon')->nullable(),
            TextInput::make('order')->numeric()->default(0),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('level.name')->label('Level')->sortable(),
            TextColumn::make('name')->searchable(),
            ColorColumn::make('color'),
            TextColumn::make('order')->sortable(),
            ToggleColumn::make('is_active'),
        ])->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSubjects::route('/'),
            'create' => CreateSubject::route('/create'),
            'edit'   => EditSubject::route('/{record}/edit'),
        ];
    }
}
