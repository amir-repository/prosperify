<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RescueResource\Pages;
use App\Filament\Resources\RescueResource\RelationManagers;
use App\Filament\Resources\RescueResource\RelationManagers\FoodsRelationManager;
use App\Models\Rescue;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;

class RescueResource extends Resource
{
    protected static ?string $model = Rescue::class;

    protected static ?string $navigationGroup = 'Rescue Management';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Checkbox::make('rescue_in_office')->columnSpan(2)
                    ->live()
                    ->afterStateUpdated(
                        function (Set $set, ?string $state) {
                            $set('pickup_address', 'Kantor Food Bank');
                            $set('rescue_status_id', 6);
                        }
                    ),
                TextInput::make('title')->required(),
                DateTimePicker::make('rescue_date')->required(),
                TextInput::make('description')->required()->columnSpan(2),
                TextInput::make('pickup_address')->required(),
                Select::make('rescue_status_id')->relationship(name: 'rescueStatus', titleAttribute: 'name')->preload(),
                Select::make('user_id')->relationship(name: 'user', titleAttribute: 'name')->searchable()->required()->preload(),
                TextInput::make('donor_name')->required(),
                TextInput::make('phone')->required(),
                TextInput::make('email')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Report Time')->dateTime()->sortable(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('donor_name')->searchable(),
                TextColumn::make('pickup_address')->searchable(),
                TextColumn::make('rescue_date')->sortable(),
                TextColumn::make('rescueStatus.name'),
            ])
            ->filters([
                SelectFilter::make('Rescue Status')
                    ->relationship('rescueStatus', 'name')->preload(),
                Filter::make('rescue_at')
                    ->form([
                        DatePicker::make('rescue_from'),
                        DatePicker::make('rescue_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['rescue_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('rescue_date', '>=', $date),
                            )
                            ->when(
                                $data['rescue_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('rescue_date', '<=', $date),
                            );
                    })
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
            FoodsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRescues::route('/'),
            'create' => Pages\CreateRescue::route('/create'),
            'edit' => Pages\EditRescue::route('/{record}/edit'),
        ];
    }
}
