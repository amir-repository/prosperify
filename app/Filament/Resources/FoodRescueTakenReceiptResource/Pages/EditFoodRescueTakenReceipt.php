<?php

namespace App\Filament\Resources\FoodRescueTakenReceiptResource\Pages;

use App\Filament\Resources\FoodRescueTakenReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodRescueTakenReceipt extends EditRecord
{
    protected static string $resource = FoodRescueTakenReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
