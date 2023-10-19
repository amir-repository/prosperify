<?php

namespace App\Filament\Resources\FoodDonationGivenReceiptResource\Pages;

use App\Filament\Resources\FoodDonationGivenReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodDonationGivenReceipt extends EditRecord
{
    protected static string $resource = FoodDonationGivenReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
