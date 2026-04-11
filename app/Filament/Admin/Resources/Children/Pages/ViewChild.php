<?php
namespace App\Filament\Admin\Resources\Children\Pages;

use App\Filament\Admin\Resources\ChildResource;
use App\Filament\Admin\Resources\Children\Schemas\ChildInfolist;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewChild extends ViewRecord
{
    protected static string $resource = ChildResource::class;

    public function infolist(Schema $infolist): Schema
    {
        return ChildInfolist::configure($infolist);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
