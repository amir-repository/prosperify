<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodResource\Pages;
use App\Filament\Resources\FoodResource\RelationManagers;
use App\Filament\Resources\FoodResource\RelationManagers\FoodRescueLogsRelationManager;
use App\Models\Food;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationGroup = 'Food Management';

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photo')
                    ->columnSpan(2)->required(),
                TextInput::make('name')->required(),
                DateTimePicker::make('expired_date')->required(),
                TextInput::make('detail')->required()->columnSpan(2),
                TextInput::make('amount')->numeric()->required(),
                Select::make('unit_id')
                    ->relationship(name: 'unit', titleAttribute: 'name')->required()->preload(),
                Select::make('sub_category_id')
                    ->relationship(name: 'subCategory', titleAttribute: 'name')->required()->label('Group of')->preload()->searchable(),
                Select::make('food_rescue_status_id')
                    ->relationship(name: 'foodRescueStatus', titleAttribute: 'name')->required(),
                Select::make('vault_id')
                    ->relationship(name: 'vault', titleAttribute: 'name')->required()->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                ImageColumn::make('photo')->square(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('amount')->searchable()->sortable(),
                TextColumn::make('unit.name')->sortable(),
                TextColumn::make('foodRescueStatus.name')->label('Rescue Status'),
                TextColumn::make('category.name'),
                TextColumn::make('subCategory.name')->label('Sub Category'),
                TextColumn::make('stored_at')->date()->label('Stored At'),
                TextColumn::make('vault.name'),
                TextColumn::make('expired_date')->date()
            ])
            ->filters([
                Filter::make('Is Stored')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('stored_at'))->toggle()->default(),
                Filter::make('Expired in 7 days')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('expired_date', [Carbon::now(), Carbon::now()->addDays(6)]))->toggle(),
                Filter::make('Expired in 30 days')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('expired_date', [Carbon::now(), Carbon::now()->addDays(29)]))->toggle(),
                SelectFilter::make('Category')
                    ->relationship('category', 'name')
                    ->preload(),
                SelectFilter::make('Sub Category')
                    ->relationship('subCategory', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('Vault')
                    ->relationship('vault', 'name')
                    ->preload(),
                SelectFilter::make('Rescue Status')
                    ->relationship('foodRescueStatus', 'name')->searchable()
                    ->preload(),
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
            FoodRescueLogsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFood::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit' => Pages\EditFood::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
