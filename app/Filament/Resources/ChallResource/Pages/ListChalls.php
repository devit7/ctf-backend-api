<?php

namespace App\Filament\Resources\ChallResource\Pages;

use App\Filament\Resources\ChallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChalls extends ListRecords
{
    protected static string $resource = ChallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
