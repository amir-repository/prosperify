<?php

namespace App\Filament\Resources\FoodDiffResource\Pages;

use App\Filament\Resources\FoodDiffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodDiffs extends ListRecords
{
    protected static string $resource = FoodDiffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
