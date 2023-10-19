<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationFoodDiffResource\Pages;
use App\Filament\Resources\DonationFoodDiffResource\RelationManagers;
use App\Models\DonationFoodDiff;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationFoodDiffResource extends Resource
{
    protected static ?string $model = DonationFoodDiff::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Donation Management';

    protected static ?string $navigationLabel = 'Diff';

    protected static ?int $navigationSort = 5;

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
                TextColumn::make('donationFood.donation.recipient.name')->searchable(),
                TextColumn::make('donationFood.donation.title')->searchable(),
                TextColumn::make('donationFood.food.name')->searchable(),
                TextColumn::make('amount'),
                TextColumn::make('donationFood.food.unit.name'),
                TextColumn::make('onFoodDonationStatus.name')->label('When food is'),
                TextColumn::make('created_at')->dateTime()->label('Date'),
                TextColumn::make('actor_name')->label('Actor'),
                TextColumn::make('foodDonationStatus.name')->label('Status'),
            ])
            ->filters([
                Filter::make('Today Donation')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', Carbon::today()))->toggle(),
                Filter::make('Yesterday Donation')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', Carbon::today()->subDays(1)))->toggle(),
                Filter::make('Past-7 days Donation')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]))->toggle(),
                Filter::make('Past-30 days Donation')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]))->toggle(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonationFoodDiffs::route('/'),
            'create' => Pages\CreateDonationFoodDiff::route('/create'),
            'edit' => Pages\EditDonationFoodDiff::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
