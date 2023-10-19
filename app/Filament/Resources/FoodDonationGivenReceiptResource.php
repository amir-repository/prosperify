<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodDonationGivenReceiptResource\Pages;
use App\Filament\Resources\FoodDonationGivenReceiptResource\RelationManagers;
use App\Models\FoodDonationGivenReceipt;
use Carbon\Carbon;
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

class FoodDonationGivenReceiptResource extends Resource
{
    protected static ?string $model = FoodDonationGivenReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Donation Management';

    protected static ?string $navigationLabel = 'Given Receipt';

    protected static ?int $navigationSort = 4;

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
                TextColumn::make('donationAssignment.volunteer.name')->searchable(),
                TextColumn::make('donationAssignment.assigner.name')->label('Admin')->searchable(),
                TextColumn::make('donationAssignment.donationFood.donation.recipient.name')->searchable(),
                TextColumn::make('donationAssignment.donationFood.donation.title')->searchable(),
                TextColumn::make('donationAssignment.donationFood.food.name')->searchable(),
                TextColumn::make('given_amount')->label("Given Amount"),
                TextColumn::make('donationAssignment.donationFood.food.unit.name'),
                ImageColumn::make('recipient_signature')->label('Recipient Signature'),
                TextColumn::make('created_at')->dateTime()->label('Signed At'),
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
            'index' => Pages\ListFoodDonationGivenReceipts::route('/'),
            'create' => Pages\CreateFoodDonationGivenReceipt::route('/create'),
            'edit' => Pages\EditFoodDonationGivenReceipt::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
