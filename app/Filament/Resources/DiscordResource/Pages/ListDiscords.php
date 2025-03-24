<?php

namespace App\Filament\Resources\DiscordResource\Pages;

use App\Filament\Resources\DiscordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscords extends ListRecords
{
    protected static string $resource = DiscordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
