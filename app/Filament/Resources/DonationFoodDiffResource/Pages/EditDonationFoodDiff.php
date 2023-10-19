<?php

namespace App\Filament\Resources\DonationFoodDiffResource\Pages;

use App\Filament\Resources\DonationFoodDiffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonationFoodDiff extends EditRecord
{
    protected static string $resource = DonationFoodDiffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
