<?php

namespace App\Filament\Resources\FoodRescueStoredReceiptResource\Pages;

use App\Filament\Resources\FoodRescueStoredReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodRescueStoredReceipt extends EditRecord
{
    protected static string $resource = FoodRescueStoredReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
