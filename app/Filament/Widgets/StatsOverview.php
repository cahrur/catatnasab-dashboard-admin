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
        // Mendapatkan nilai filter tanggal
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Mendapatkan periode sebelumnya
        $previousStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->subMonth() : null;
        $previousEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->subMonth() : null;

        // Menghitung total penjualan periode saat ini
        $totalPenjualan = Order::query()
            ->join('plans', 'orders.plan_id', '=', 'plans.id')
            ->when($startDate, fn (Builder $query) => $query->whereDate('orders.created_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->whereDate('orders.created_at', '<=', $endDate))
            ->where('orders.status', 'paid')
            ->sum('plans.price');

        // Menghitung total penjualan periode sebelumnya
        $totalPenjualanBulanLalu = Order::query()
        ->join('plans', 'orders.plan_id', '=', 'plans.id')
        ->when($previousStartDate, fn (Builder $query) => $query->whereDate('orders.created_at', '>=', $previousStartDate))
        ->when($previousEndDate, fn (Builder $query) => $query->whereDate('orders.created_at', '<=', $previousEndDate))
        ->where('orders.status', 'paid') // Pastikan hanya mengambil orders dengan status 'paid'
        ->sum('plans.price');

        // Menghitung persentase peningkatan
        $increase = $totalPenjualanBulanLalu > 0
        ? (($totalPenjualan - $totalPenjualanBulanLalu) / $totalPenjualanBulanLalu) * 100
        : 0;

        // Format total penjualan
        $totalPenjualanFormatted = number_format($totalPenjualan, 0, ',', '.');


        return [
            Stat::make(
                label: 'Total Penjualan',
                value: 'Rp ' . $totalPenjualanFormatted,
            )
            ->description(number_format($increase, 2) . '% increase')
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
