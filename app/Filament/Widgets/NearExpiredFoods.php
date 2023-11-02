<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class NearExpiredFoods extends BaseWidget
{
    protected array|string|int $columnSpan = 'half';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Food::query()->latest()
            )
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
                TextColumn::make('expired_date')->date()->sortable()
            ])->filters([
                Filter::make('Is Stored')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('stored_at'))->toggle()->default(),
                Filter::make('Expired in 7 days')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('expired_date', [Carbon::now(), Carbon::now()->addDays(6)]))->toggle()->default(),
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
            ]);
    }
}
