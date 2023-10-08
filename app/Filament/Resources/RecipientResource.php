<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipientResource\Pages;
use App\Filament\Resources\RecipientResource\RelationManagers;
use App\Filament\Resources\RecipientResource\RelationManagers\RecipientLogsRelationManager;
use App\Models\Recipient;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Contracts\Service\Attribute\Required;

class RecipientResource extends Resource
{
    protected static ?string $model = Recipient::class;

    protected static ?string $navigationGroup = 'Recipient Management';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->directory('recipient-documentations')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('name')->required(),
                TextInput::make('nik')->required()->numeric(),
                TextInput::make('address')->required(),
                TextInput::make('phone')->required()
                    ->numeric(),
                TextInput::make('family_members')->required()->numeric(),
                Select::make('recipient_status_id')->relationship(name: 'recipientStatus', titleAttribute: 'name')->default('2')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')->square(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('nik')->label('NIK'),
                TextColumn::make('address'),
                TextColumn::make('phone'),
                TextColumn::make('family_members')->label('Members'),
                TextColumn::make('recipientStatus.name')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RecipientLogsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipients::route('/'),
            'create' => Pages\CreateRecipient::route('/create'),
            'edit' => Pages\EditRecipient::route('/{record}/edit'),
        ];
    }
}
