<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VaultResource\Pages;
use App\Filament\Resources\VaultResource\RelationManagers;
use App\Models\Vault;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VaultResource extends Resource
{
    protected static ?string $model = Vault::class;

    protected static ?string $navigationGroup = 'Food Management';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->columnSpan(2),
                TextInput::make('address')->required(),
                Select::make('city_id')
                    ->relationship(name: 'city', titleAttribute: 'name')->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('address'),
                TextColumn::make('city.name')
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
            'index' => Pages\ListVaults::route('/'),
            'create' => Pages\CreateVault::route('/create'),
            'edit' => Pages\EditVault::route('/{record}/edit'),
        ];
    }
}
