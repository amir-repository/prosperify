<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use App\Models\FoodDonationLog;
use App\Models\FoodRescueLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RescueStatsOverview extends BaseWidget
{

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Food Saved', FoodRescueLog::where(['unit_id' => 1, 'food_rescue_status_id' => 10])->get()->map(fn ($x) => $x->amount)->sum() . ' Kg'),
            Stat::make('Total Food Saved', FoodRescueLog::where(['unit_id' => 2, 'food_rescue_status_id' => 10])->get()->map(fn ($x) => $x->amount)->sum() . ' Serving'),
            Stat::make('Total Food Donated', FoodDonationLog::where(['unit_id' => 1, 'food_donation_status_id' => 7])->get()->map(fn ($x) => $x->amount)->sum() . ' Kg'),
            Stat::make('Total Food Donated', FoodDonationLog::where(['unit_id' => 2, 'food_donation_status_id' => 7])->get()->map(fn ($x) => $x->amount)->sum() . ' Serving'),
        ];
    }
}
