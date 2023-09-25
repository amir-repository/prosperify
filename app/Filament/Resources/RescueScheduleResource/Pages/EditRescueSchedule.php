<?php

namespace App\Filament\Resources\RescueScheduleResource\Pages;

use App\Filament\Resources\RescueScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRescueSchedule extends EditRecord
{
    protected static string $resource = RescueScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
