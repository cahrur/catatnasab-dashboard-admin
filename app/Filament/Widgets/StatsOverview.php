<?php

namespace App\Filament\Widgets;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    
    use InteractsWithPageFilters;
    
    protected function getStats(): array
    {
        $totalPenjualan = Order::where('status', 'paid')->sum('amount');
        $totalPenjualanFormatted = 'Rp ' . number_format($totalPenjualan, 2, ',', '.');
        $totalCustomer = User::where('is_admin', 0)->count();

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        return [
            Stat::make('Total Penjualan', $totalPenjualanFormatted)
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Stat::make('Total Pesanan', Order::count()),
            Stat::make('Total Customer', $totalCustomer),
        ];
    }
}
