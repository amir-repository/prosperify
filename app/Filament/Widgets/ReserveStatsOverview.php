<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use App\Models\Recipient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReserveStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Food Reserve', Food::where('unit_id', 1)->get()->filter(fn ($x) => in_array($x->food_rescue_status_id, [10, 12]))->map(fn ($x) => $x->amount)->sum() . ' Kg'),
            Stat::make('Food Reserve', Food::where('unit_id', 2)->get()->filter(fn ($x) => in_array($x->food_rescue_status_id, [10, 12]))->map(fn ($x) => $x->amount)->sum() . ' Serving'),
            Stat::make('Recipient', Recipient::where('recipient_status_id', 2)->get()->count() . ' Family'),
            Stat::make('Recipient', Recipient::where('recipient_status_id', 2)->get()->map(fn ($x) => $x->family_members)->sum() . ' Person'),

        ];
    }
}
