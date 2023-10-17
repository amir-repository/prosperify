<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use App\Models\DonationLog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDonation extends CreateRecord
{
    protected static string $resource = DonationResource::class;

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
        $donation = $this->record;
        $user = auth()->user();

        DonationLog::Create($donation, $user);
    }
}
