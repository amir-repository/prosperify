<?php

namespace App\Filament\Resources\FoodResource\Pages;

use App\Filament\Resources\FoodResource;
use App\Models\Food;
use App\Models\FoodRescueLog;
use App\Models\Rescue;
use App\Models\Vault;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFood extends EditRecord
{
    protected static string $resource = FoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['food_rescue_status_id'] = Food::ADJUSTED_AFTER_STORED;

        return $data;
    }

    protected function afterSave(): void
    {
        $user = auth()->user();
        $food = $this->record;
        $rescue = Rescue::find($food->rescue_id);
        $vault = Vault::find($food->vault_id);
        FoodRescueLog::Create($user, $rescue, $food, $vault);
    }
}
