<?php

namespace App\Filament\Resources\FoodDonationReportResource\Pages;

use App\Filament\Resources\FoodDonationReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodDonationReports extends ListRecords
{
    protected static string $resource = FoodDonationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
