<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use App\Models\FoodRescueLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class YearlyFoodSavedChart extends ChartWidget
{
    protected static ?string $heading = 'Yearly Food Rescue Chart';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::query(FoodRescueLog::where(['unit_id' => 1, 'food_rescue_status_id' => 10])->orWhere(['unit_id' => 1, 'food_rescue_status_id' => 12]))->dateColumn('created_at')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('amount');

        $serving = Trend::query(FoodRescueLog::where(['unit_id' => 2, 'food_rescue_status_id' => 10])->orWhere(['unit_id' => 2, 'food_rescue_status_id' => 12]))->dateColumn('created_at')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('amount');

        $items = Trend::query(FoodRescueLog::where(['food_rescue_status_id' => 10])->orWhere(['food_rescue_status_id' => 12]))->dateColumn('created_at')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total items ',
                    'data' => $items->map(fn (TrendValue $value) => $value->aggregate),
                ],
                [
                    'label' => 'Serving ',
                    'data' => $serving->map(fn (TrendValue $value) => $value->aggregate / 1000),
                    'borderColor' => '#6d28d9',
                ],
                [
                    'label' => 'Kg ',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate / 1000),
                    'borderColor' => '#059669',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
