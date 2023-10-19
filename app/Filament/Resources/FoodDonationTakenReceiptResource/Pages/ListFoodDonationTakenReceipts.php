<?php

namespace App\Filament\Resources\FoodDonationTakenReceiptResource\Pages;

use App\Filament\Resources\FoodDonationTakenReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoodDonationTakenReceipts extends ListRecords
{
    protected static string $resource = FoodDonationTakenReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
