<?php

namespace App\Filament\Resources\FoodDiffResource\Pages;

use App\Filament\Resources\FoodDiffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoodDiff extends EditRecord
{
    protected static string $resource = FoodDiffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
