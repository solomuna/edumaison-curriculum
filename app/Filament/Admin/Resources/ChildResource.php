<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Children\Pages\CreateChild;
use App\Filament\Admin\Resources\Children\Pages\EditChild;
use App\Filament\Admin\Resources\Children\Pages\ListChildren;
use App\Filament\Admin\Resources\Children\Pages\ViewChild;
use App\Models\Child;
use App\Models\Level;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ChildResource extends Resource
{
    protected static ?string $model = Child::class;

    public static function form(Schema $form): Schema
    {
        return $form->components([
            FileUpload::make('avatar')
                ->label('Photo de profil')
                ->image()
                ->disk('public')
                ->directory('avatars')
                ->imageEditor()
                ->circleCropper()
                ->nullable(),
            TextInput::make('first_name')->required()->maxLength(255),
            TextInput::make('last_name')->required()->maxLength(255),
            DatePicker::make('birth_date')->required(),
            Select::make('level_id')
                ->label('Level')
                ->options(Level::all()->pluck('name', 'id'))
                ->required(),
            TextInput::make('pin')
                ->label('PIN (4 chiffres)')
                ->maxLength(4)
                ->nullable(),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('avatar')
                ->circular()
                ->disk('public')
                ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->first_name) . '&background=1D6B2A&color=fff'),
            TextColumn::make('first_name')->searchable(),
            TextColumn::make('last_name')->searchable(),
            TextColumn::make('level.name')->label('Level')->sortable(),
            TextColumn::make('birth_date')->date(),
            ToggleColumn::make('is_active'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListChildren::route('/'),
            'view'   => ViewChild::route('/{record}'),
            'create' => CreateChild::route('/create'),
            'edit'   => EditChild::route('/{record}/edit'),
        ];
    }
}
