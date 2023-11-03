<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodDonationReportResource\Pages;
use App\Filament\Resources\FoodDonationReportResource\RelationManagers;
use App\Models\FoodDonationLog;
use App\Models\FoodDonationReport;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodDonationReportResource extends Resource
{
    protected static ?string $model = FoodDonationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Donation Management';

    protected static ?string $navigationLabel = 'Donation Report';

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
                TextColumn::make('donationFood.donation.title')->searchable(),
                TextColumn::make('donationFood.donation.recipient.address')->searchable()->label('Donation Address'),
                TextColumn::make('donationFood.food.name')->searchable(),
                TextColumn::make('food_donation_status_name')->label('Donation Status')->searchable(),
                TextColumn::make('stored_food_amount')->label('Stored Amount'),
                TextColumn::make('amount')->label('Donation Amount')->summarize(
                    Sum::make()->query(fn (QueryBuilder $query) => $query->where(['food_donation_status_id' => 7]))->label('Total'),
                ),
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
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]))->toggle()->default(),
                SelectFilter::make('Donation Status')
                    ->relationship('foodDonationStatus', 'name')->searchable()
                    ->preload()->default(7),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultGroup('unit_name');
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
            'index' => Pages\ListFoodDonationReports::route('/'),
            'create' => Pages\CreateFoodDonationReport::route('/create'),
            'edit' => Pages\EditFoodDonationReport::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
