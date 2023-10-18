<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationScheduleResource\Pages;
use App\Filament\Resources\DonationScheduleResource\RelationManagers;
use App\Models\DonationSchedule;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationScheduleResource extends Resource
{
    protected static ?string $model = DonationSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Donation Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label("Volunteer")->searchable(),
                TextColumn::make('donationFood.donation.title')->searchable(),
                TextColumn::make('donationFood.food.name')->searchable(),
                TextColumn::make('donationFood.amount')->label("Amount"),
                TextColumn::make('donationFood.food.unit.name'),
                TextColumn::make('donation_date')->dateTime()->label("Schedule"),
                TextColumn::make('donationFood.donation.recipient.name')->searchable(),
                TextColumn::make('donationFood.foodDonationStatus.name')->label("status")->searchable(),
            ])
            ->filters([
                Filter::make('Today Donation')->query(fn (Builder $query): Builder => $query->whereDate('donation_date', Carbon::today()))->toggle(),
                Filter::make('Tomorrow Donation')->query(fn (Builder $query): Builder => $query->whereDate('donation_date', Carbon::today()->addDays(1)))->toggle(),
                Filter::make("Next-7 Day's Donation")->query(fn (Builder $query): Builder => $query->whereBetween('donation_date', [Carbon::today(), Carbon::today()->addDays(7)]))->toggle(),
                Filter::make("Next-30 Day's Donation")->query(fn (Builder $query): Builder => $query->whereBetween('donation_date', [Carbon::today(), Carbon::today()->addDays(30)]))->toggle(),
                SelectFilter::make('Volunteer')->relationship('user', 'name')->searchable()->preload(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonationSchedules::route('/'),
            'create' => Pages\CreateDonationSchedule::route('/create'),
            'edit' => Pages\EditDonationSchedule::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
