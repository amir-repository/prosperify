<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RescueScheduleResource\Pages;
use App\Filament\Resources\RescueScheduleResource\RelationManagers;
use App\Models\RescueSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                TextColumn::make('user.name')->label('Volunteer'),
                TextColumn::make('food.id')->label('Food ID'),
                TextColumn::make('food.name'),
                TextColumn::make('rescue_date')->dateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRescueSchedules::route('/'),
            'create' => Pages\CreateRescueSchedule::route('/create'),
            'edit' => Pages\EditRescueSchedule::route('/{record}/edit'),
        ];
    }
}
