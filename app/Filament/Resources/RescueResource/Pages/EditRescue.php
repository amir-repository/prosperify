<?php

namespace App\Filament\Resources\RescueResource\Pages;

use App\Filament\Resources\RescueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRescue extends EditRecord
{
    protected static string $resource = RescueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
