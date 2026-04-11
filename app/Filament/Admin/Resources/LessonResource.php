<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Lessons\Pages\CreateLesson;
use App\Filament\Admin\Resources\Lessons\Pages\EditLesson;
use App\Filament\Admin\Resources\Lessons\Pages\ListLessons;
use App\Models\Lesson;
use App\Models\Unit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Select::make('unit_id')
                ->label('Unit')
                ->options(Unit::all()->pluck('name', 'id'))
                ->required(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            Textarea::make('description')->nullable(),
            TextInput::make('order')->numeric()->default(0),
            TextInput::make('estimated_minutes')->numeric()->nullable(),
            Select::make('type')
                ->options([
                    'reading'     => 'Reading',
                    'listening'   => 'Listening',
                    'speaking'    => 'Speaking',
                    'writing'     => 'Writing',
                    'mathematics' => 'Mathematics',
                    'science'     => 'Science',
                    'ict'         => 'ICT',
                    'mixed'       => 'Mixed',
                ])->default('mixed'),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('unit.name')->label('Unit')->sortable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('type'),
            TextColumn::make('order')->sortable(),
            ToggleColumn::make('is_active'),
        ])->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLessons::route('/'),
            'create' => CreateLesson::route('/create'),
            'edit'   => EditLesson::route('/{record}/edit'),
        ];
    }
}
