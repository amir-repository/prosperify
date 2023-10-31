<?php

namespace App\Filament\Resources\FoodRescueLogResource\Pages;

use App\Filament\Resources\FoodRescueLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodRescueLog extends EditRecord
{
    protected static string $resource = FoodRescueLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
