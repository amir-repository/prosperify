<?php

namespace App\Filament\Resources\FoodDonationTakenReceiptResource\Pages;

use App\Filament\Resources\FoodDonationTakenReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodDonationTakenReceipt extends EditRecord
{
    protected static string $resource = FoodDonationTakenReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
