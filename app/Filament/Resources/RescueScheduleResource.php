<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RescueScheduleResource\Pages;
use App\Filament\Resources\RescueScheduleResource\RelationManagers;
use App\Models\RescueSchedule;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RescueScheduleResource extends Resource
{
    protected static ?string $model = RescueSchedule::class;

    protected static ?string $navigationGroup = 'Rescue Management';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

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
                TextColumn::make('user.name')->label('Volunteer')->searchable(),
                TextColumn::make('food.rescue.title')->searchable(),
                TextColumn::make('food.name')->searchable(),
                TextColumn::make('food.amount'),
                TextColumn::make('food.unit.name'),
                TextColumn::make('rescue_date')->dateTime()->label("Schedule")->sortable(),
                TextColumn::make('food.rescue.donor_name')->label('Donor')->searchable(),
                TextColumn::make('food.foodRescueStatus.name')->label('Status')->searchable(),

            ])
            ->filters([
                Filter::make('Today Donation')->query(fn (Builder $query): Builder => $query->whereDate('rescue_date', Carbon::today()))->toggle(),
                Filter::make('Tomorrow Donation')->query(fn (Builder $query): Builder => $query->whereDate('rescue_date', Carbon::today()->addDays(1)))->toggle(),
                Filter::make('Next-7 days Rescue')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now(), Carbon::now()->addDays(7)]))
                    ->toggle(),
                Filter::make('Next-30 days Rescue')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [Carbon::now(), Carbon::now()->addDays(30)]))
                    ->toggle(),
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
            'index' => Pages\ListRescueSchedules::route('/'),
            'create' => Pages\CreateRescueSchedule::route('/create'),
            'edit' => Pages\EditRescueSchedule::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
