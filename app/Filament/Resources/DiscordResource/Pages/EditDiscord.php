<?php

namespace App\Filament\Resources\DiscordResource\Pages;

use App\Filament\Resources\DiscordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscord extends EditRecord
{
    protected static string $resource = DiscordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
