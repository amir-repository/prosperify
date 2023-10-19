<?php

namespace App\Filament\Resources\DonationFoodDiffResource\Pages;

use App\Filament\Resources\DonationFoodDiffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonationFoodDiffs extends ListRecords
{
    protected static string $resource = DonationFoodDiffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
