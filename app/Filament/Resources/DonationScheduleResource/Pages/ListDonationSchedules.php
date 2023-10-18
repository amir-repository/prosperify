<?php

namespace App\Filament\Resources\DonationScheduleResource\Pages;

use App\Filament\Resources\DonationScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonationSchedules extends ListRecords
{
    protected static string $resource = DonationScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
