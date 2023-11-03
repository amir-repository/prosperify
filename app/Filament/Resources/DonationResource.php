<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Filament\Resources\DonationResource\RelationManagers\DonationFoodsRelationManager;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Donation Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                TextInput::make('description')->required(),
                DateTimePicker::make('donation_date')->required(),
                Select::make('user_id')->relationship(name: 'user', titleAttribute: 'name')->searchable()->preload()->label('Actor')->required(),
                Select::make('donation_status_id')->relationship(name: 'donationStatus', titleAttribute: 'name')->searchable()->preload()->default(4)->required(),
                Select::make('recipient_id')->relationship(name: 'recipient', titleAttribute: 'name')->searchable()->preload()->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('recipient.name')->label('Recipient')->searchable(),
                TextColumn::make('recipient.nik')->label('NIK')->searchable(),
                TextColumn::make('recipient.address')->label('Donation Address'),
                TextColumn::make('donation_date')->dateTime()->label('Donation Date')->sortable(),
                TextColumn::make('donationStatus.name')->label('Status'),
            ])
            ->filters([
                SelectFilter::make('Donation Status')
                    ->relationship('donationStatus', 'name')->preload(),
                Filter::make('donated_at')
                    ->form([
                        DatePicker::make('donated_from'),
                        DatePicker::make('donated_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['donated_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('donation_date', '>=', $date),
                            )
                            ->when(
                                $data['donated_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('donation_date', '<=', $date),
                            );
                    })
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
            DonationFoodsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
