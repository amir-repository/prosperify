<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodRescueStoredReceiptResource\Pages;
use App\Filament\Resources\FoodRescueStoredReceiptResource\RelationManagers;
use App\Models\FoodRescueStoredReceipt;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodRescueStoredReceiptResource extends Resource
{
    protected static ?string $model = FoodRescueStoredReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Rescue Management';

    protected static ?string $navigationLabel = 'Stored Receipt';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rescueAssignment.volunteer.name')->searchable(),
                TextColumn::make('rescueAssignment.assigner.name')->searchable(),
                TextColumn::make('rescueAssignment.rescue.donor_name')->label('Donor')->searchable(),
                TextColumn::make('rescueAssignment.rescue.title'),
                TextColumn::make('rescueAssignment.food.name'),
                TextColumn::make('stored_amount')->label("Stored Amount"),
                TextColumn::make('rescueAssignment.food.unit.name'),
                ImageColumn::make('admin_signature'),
                TextColumn::make('created_at')->label('Signed At')->dateTime()->sortable(),
            ])
            ->filters([
                Filter::make('Today Rescue')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', Carbon::today()))->toggle(),
                Filter::make('Yesterday Rescue')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', Carbon::today()->subDays(1)))->toggle(),
                Filter::make('Past-7 days Rescue')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]))->toggle(),
                Filter::make('Past-30 days Rescue')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]))->toggle(),
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
            'index' => Pages\ListFoodRescueStoredReceipts::route('/'),
            'create' => Pages\CreateFoodRescueStoredReceipt::route('/create'),
            'edit' => Pages\EditFoodRescueStoredReceipt::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
