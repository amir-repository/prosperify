<?php

namespace App\Filament\Resources\FoodResource\Pages;

use App\Filament\Resources\FoodResource;
use App\Models\Food;
use App\Models\FoodDiff;
use App\Models\FoodRescueLog;
use App\Models\Rescue;
use App\Models\SubCategory;
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
        $subCategory = SubCategory::find($data['sub_category_id']);
        $data['category_id'] = $subCategory->category->id;
        $data['food_rescue_status_id'] = Food::ADJUSTED_AFTER_STORED;

        // if there's a different in food amount
        $food = $this->record;
        $change_amount = ((float)$data['amount']);
        $amount = $food->amount;
        if ($amount !== $change_amount) {
            $diff = $amount - $change_amount;
            FoodDiff::Create($food, $diff, auth()->user());
        }

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
