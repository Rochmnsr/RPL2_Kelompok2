<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Shipped Orders', Order::query()->where('status', 'shipped')->count()),
        ];

        $averageGrandTotal = Order::query()->avg('grand_total');

        if ($averageGrandTotal !== null) {
            // Format rata-rata grand total menjadi mata uang IDR
            $formattedAverage = 'IDR ' . number_format($averageGrandTotal, 2);

            $stats[] = Stat::make('Average Price', $formattedAverage);
        } else {
            // Handle jika nilai rata-rata grand total adalah null
            $stats[] = Stat::make('Average Price', 'N/A');
        }

        return $stats;
    }
}
