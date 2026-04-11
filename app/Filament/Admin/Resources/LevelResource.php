<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Levels\Pages\CreateLevel;
use App\Filament\Admin\Resources\Levels\Pages\EditLevel;
use App\Filament\Admin\Resources\Levels\Pages\ListLevels;
use App\Models\Level;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    public static function form(Schema $form): Schema
    {
        return $form->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('order')->required()->numeric()->default(0),
            Select::make('cycle')
                ->required()
                ->options([
                    'pre_nursery' => 'Pre-Nursery',
                    'nursery'     => 'Nursery',
                    'primary'     => 'Primary',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable(),
            TextColumn::make('cycle'),
            TextColumn::make('order')->sortable(),
        ])->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLevels::route('/'),
            'create' => CreateLevel::route('/create'),
            'edit'   => EditLevel::route('/{record}/edit'),
        ];
    }
}