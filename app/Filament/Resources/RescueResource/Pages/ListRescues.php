<?php

namespace App\Filament\Resources\RescueResource\Pages;

use App\Filament\Resources\RescueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRescues extends ListRecords
{
    protected static string $resource = RescueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
