<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodDiffResource\Pages;
use App\Filament\Resources\FoodDiffResource\RelationManagers;
use App\Models\FoodDiff;
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

class FoodDiffResource extends Resource
{
    protected static ?string $model = FoodDiff::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Rescue Management';

    protected static ?string $navigationLabel = 'Diff';

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
                TextColumn::make('food.rescue.user.name')->label('Donor')->searchable(),
                TextColumn::make('food.rescue.title')->searchable(),
                TextColumn::make('food.name')->searchable(),
                TextColumn::make('amount'),
                TextColumn::make('food.unit.name'),
                TextColumn::make('onFoodRescueStatus.name')->label('When food is'),
                TextColumn::make('created_at')->dateTime()->label('Date'),
                TextColumn::make('actor_name')->label('Actor')->searchable(),
                TextColumn::make('foodRescueStatus.name')->label('Status'),

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
            'index' => Pages\ListFoodDiffs::route('/'),
            'create' => Pages\CreateFoodDiff::route('/create'),
            'edit' => Pages\EditFoodDiff::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
