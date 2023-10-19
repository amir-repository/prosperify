<?php

namespace App\Filament\Resources\FoodDonationGivenReceiptResource\Pages;

use App\Filament\Resources\FoodDonationGivenReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodDonationGivenReceipts extends ListRecords
{
    protected static string $resource = FoodDonationGivenReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
