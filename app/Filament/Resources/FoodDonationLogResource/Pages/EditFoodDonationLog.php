<?php

namespace App\Filament\Resources\FoodDonationLogResource\Pages;

use App\Filament\Resources\FoodDonationLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodDonationLog extends EditRecord
{
    protected static string $resource = FoodDonationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
