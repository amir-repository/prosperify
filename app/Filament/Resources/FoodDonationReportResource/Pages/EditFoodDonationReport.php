<?php

namespace App\Filament\Resources\FoodDonationReportResource\Pages;

use App\Filament\Resources\FoodDonationReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodDonationReport extends EditRecord
{
    protected static string $resource = FoodDonationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
