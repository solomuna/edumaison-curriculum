<?php
namespace App\Filament\Admin\Resources\Children\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ChildInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('first_name')->label('Prénom'),
                        TextEntry::make('last_name')->label('Nom'),
                        TextEntry::make('birth_date')->label('Date de naissance')->date(),
                        TextEntry::make('level.name')->label('Classe'),
                    ]),

                Section::make('Bulletin de notes')
                    ->schema([
                        RepeatableEntry::make('schoolResults')
                            ->label('')
                            ->schema([
                                TextEntry::make('subject.name')
                                    ->label('Matière')
                                    ->weight('bold'),
                                TextEntry::make('average_score')
                                    ->label('Moyenne')
                                    ->suffix('/20')
                                    ->color(fn ($state) => match(true) {
                                        $state >= 15 => 'success',
                                        $state >= 10 => 'warning',
                                        default      => 'danger',
                                    }),
                                TextEntry::make('appreciation')
                                    ->label('Appréciation')
                                    ->badge()
                                    ->color(fn ($state) => match($state) {
                                        'Excellent'  => 'success',
                                        'Très bien'  => 'success',
                                        'Bien'       => 'info',
                                        'Passable'   => 'warning',
                                        default      => 'danger',
                                    }),
                                TextEntry::make('teacher_comment')
                                    ->label('Commentaire')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
