<?php

namespace App\Filament\Resources\DonationScheduleResource\Pages;

use App\Filament\Resources\DonationScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonationSchedule extends EditRecord
{
    protected static string $resource = DonationScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
