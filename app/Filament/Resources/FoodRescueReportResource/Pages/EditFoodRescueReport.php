<?php

namespace App\Filament\Resources\FoodRescueReportResource\Pages;

use App\Filament\Resources\FoodRescueReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodRescueReport extends EditRecord
{
    protected static string $resource = FoodRescueReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
