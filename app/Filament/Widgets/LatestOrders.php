<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID'),
                Tables\Columns\TextColumn::make('user.name')->label('Customer'),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan'),
                Tables\Columns\TextColumn::make('plan.price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('payment.payment_method')
                    ->label('Metode Pembayaran'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y, H:i'),
            ]);
    }
}
