<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodRescueReportResource\Pages;
use App\Filament\Resources\FoodRescueReportResource\RelationManagers;
use App\Models\Food;
use App\Models\FoodRescueLog;
use App\Models\FoodRescueReport;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodRescueReportResource extends Resource
{
    protected static ?string $model = FoodRescueLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Rescue Management';

    protected static ?string $navigationLabel = 'Rescue Report';

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
                TextColumn::make('rescue.title')->searchable(),
                TextColumn::make('food.name')->searchable(),
                TextColumn::make('food_rescue_status_name')->label('Rescue Status')->searchable(),
                TextColumn::make('amount')->summarize(
                    Sum::make()->query(fn (QueryBuilder $query) => $query->where('food_rescue_status_id', 10))
                ),
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
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]))->toggle()->default(),
                SelectFilter::make('Rescue Status')
                    ->relationship('foodRescueStatus', 'name')->searchable()
                    ->preload()->default(10),
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
            'index' => Pages\ListFoodRescueReports::route('/'),
            'create' => Pages\CreateFoodRescueReport::route('/create'),
            'edit' => Pages\EditFoodRescueReport::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
