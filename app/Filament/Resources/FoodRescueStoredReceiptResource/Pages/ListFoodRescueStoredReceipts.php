<?php

namespace App\Filament\Resources\FoodRescueStoredReceiptResource\Pages;

use App\Filament\Resources\FoodRescueStoredReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodRescueStoredReceipts extends ListRecords
{
    protected static string $resource = FoodRescueStoredReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
