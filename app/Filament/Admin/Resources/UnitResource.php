<?php
namespace App\Filament\Admin\Resources;

use App\Models\Unit;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Select::make('integrated_theme_id')
                ->relationship('integratedTheme', 'name')
                ->required()
                ->searchable(),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('order')->numeric()->default(0),
            MarkdownEditor::make('summary')
                ->label('Course Summary (Markdown)')
                ->toolbarButtons(['bold','italic','bulletList','orderedList','heading','link'])
                ->columnSpanFull()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('integratedTheme.subject.name')->label('Subject'),
            TextColumn::make('integratedTheme.name')->label('Theme'),
            TextColumn::make('order')->sortable(),
            TextColumn::make('summary')->limit(60)->label('Summary'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Admin\Resources\Units\Pages\ListUnits::route('/'),
            'create' => \App\Filament\Admin\Resources\Units\Pages\CreateUnit::route('/create'),
            'edit'   => \App\Filament\Admin\Resources\Units\Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
