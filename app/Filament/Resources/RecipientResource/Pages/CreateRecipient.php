<?php

namespace App\Filament\Resources\RecipientResource\Pages;

use App\Filament\Resources\RecipientResource;
use App\Models\RecipientLog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipient extends CreateRecord
{
    protected static string $resource = RecipientResource::class;

    protected function afterCreate(): void
    {
        $recipient = $this->record;
        $user = auth()->user();

        RecipientLog::Create($recipient, $user);
    }
}
