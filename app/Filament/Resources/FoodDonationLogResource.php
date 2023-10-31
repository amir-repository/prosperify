<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodDonationLogResource\Pages;
use App\Filament\Resources\FoodDonationLogResource\RelationManagers;
use App\Models\FoodDonationLog;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodDonationLogResource extends Resource
{
    protected static ?string $model = FoodDonationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Donation Management';

    protected static ?string $navigationLabel = 'Donation Logs';

    protected static ?int $navigationSort = 6;

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
                TextColumn::make('donationFood.donation.title')->searchable(),
                TextColumn::make('donationFood.food.name')->searchable(),
                TextColumn::make('food_donation_status_name')->label('Donation Status')->searchable(),
                TextColumn::make('stored_food_amount')->label('Stored Amount'),
                TextColumn::make('amount')->label('Donation Amount'),
                TextColumn::make('unit_name')->label('Unit'),
                TextColumn::make('actor_name'),
                ImageColumn::make('photo')->square(),
                TextColumn::make('created_at')->dateTime()->sortable()
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
            'index' => Pages\ListFoodDonationLogs::route('/'),
            'create' => Pages\CreateFoodDonationLog::route('/create'),
            'edit' => Pages\EditFoodDonationLog::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
