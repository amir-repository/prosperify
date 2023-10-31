<?php

namespace App\Filament\Resources\FoodRescueLogResource\Pages;

use App\Filament\Resources\FoodRescueLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodRescueLogs extends ListRecords
{
    protected static string $resource = FoodRescueLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
