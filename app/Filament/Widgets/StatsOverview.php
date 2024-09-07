<?php

namespace App\Filament\Widgets;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends BaseWidget
{
    
    use InteractsWithPageFilters;

    public function getStats(): array

    {

        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $totalPenjualan = Order::query()
            ->join('plans', 'orders.plan_id', '=', 'plans.id')
            ->when($startDate, fn (Builder $query) => $query->whereDate('orders.created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('orders.created_at', '<=', $endDate))
            ->where('orders.status', 'paid')
            ->sum('plans.price');

        $totalPenjualanFormatted = number_format($totalPenjualan, 0, ',', '.');


        return [
            Stat::make(
                label: 'Total Penjualan',
                value: 'Rp ' . $totalPenjualanFormatted,
            )
            ->description('32k increase') // Anda bisa menyesuaikan deskripsi ini
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'), 
            
            Stat::make(
                label: 'Total pesanan',
                value: Order::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count(),

                ),
            Stat::make(
                label: 'Total Customer',
                value: User::query()
                ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                ->count(),

            ),
        ];
    }
}
