<?php

namespace App\Filament\Resources\FoodRescueReportResource\Pages;

use App\Filament\Resources\FoodRescueReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodRescueReports extends ListRecords
{
    protected static string $resource = FoodRescueReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
