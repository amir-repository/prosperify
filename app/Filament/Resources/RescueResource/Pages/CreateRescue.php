<?php

namespace App\Filament\Resources\RescueResource\Pages;

use App\Filament\Resources\RescueResource;
use App\Models\RescueLog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRescue extends CreateRecord
{
    protected static string $resource = RescueResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }

    protected function afterCreate(): void
    {
        $rescue = $this->record;
        $user = auth()->user();

        RescueLog::Create($user, $rescue);
    }
}
