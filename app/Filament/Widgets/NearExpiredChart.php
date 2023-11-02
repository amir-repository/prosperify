<?php

namespace App\Filament\Widgets;

use App\Models\Food;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class NearExpiredChart extends ChartWidget
{
    protected static ?string $heading = 'Expired Food Trends';

    protected array|string|int $columnSpan = 'half';

    protected function getData(): array
    {
        $data = Trend::model(Food::class)->dateColumn('expired_date')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $kg = Trend::query(Food::where(['unit_id' => 1, 'food_rescue_status_id' => 10])->orWhere(['unit_id' => 1, 'food_rescue_status_id' => 12]))->dateColumn('expired_date')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )->perMonth()
            ->sum('amount');

        $serving = Trend::query(Food::where(['unit_id' => 2, 'food_rescue_status_id' => 10])->orWhere(['unit_id' => 2, 'food_rescue_status_id' => 12]))->dateColumn('expired_date')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )->perMonth()
            ->sum('amount');
        return [
            'datasets' => [
                [
                    'label' => 'Food will expired',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
                [
                    'label' => 'Serving',
                    'data' => $serving->map(fn (TrendValue $value) => $value->aggregate / 1000),
                    'borderColor' => '#6d28d9',
                ],
                [
                    'label' => 'Kg',
                    'data' => $kg->map(fn (TrendValue $value) => $value->aggregate / 1000),
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
