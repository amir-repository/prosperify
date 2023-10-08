<?php

namespace App\Filament\Resources\RecipientResource\Pages;

use App\Filament\Resources\RecipientResource;
use App\Models\RecipientLog;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecipient extends EditRecord
{
    protected static string $resource = RecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $recipient = $this->record;
        $user = auth()->user();

        RecipientLog::Create($recipient, $user);
    }
}
