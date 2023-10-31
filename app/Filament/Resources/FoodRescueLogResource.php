<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodRescueLogResource\Pages;
use App\Filament\Resources\FoodRescueLogResource\RelationManagers;
use App\Models\FoodRescueLog;
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

class FoodRescueLogResource extends Resource
{
    protected static ?string $model = FoodRescueLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Rescue Management';

    protected static ?string $navigationLabel = 'Rescue Logs';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rescue.title')->searchable(),
                TextColumn::make('food.name')->searchable(),
                TextColumn::make('food_rescue_status_name')->label('Rescue Status')->searchable(),
                TextColumn::make('amount'),
                TextColumn::make('unit_name'),
                TextColumn::make('actor_name'),
                ImageColumn::make('photo')->label('Photo')->square(),
                TextColumn::make('created_at')->dateTime()->sortable()
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
            'index' => Pages\ListFoodRescueLogs::route('/'),
            'create' => Pages\CreateFoodRescueLog::route('/create'),
            'edit' => Pages\EditFoodRescueLog::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
