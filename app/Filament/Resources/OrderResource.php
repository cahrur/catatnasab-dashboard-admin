<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\Status;
use Filament\Forms\Components\Select;


class OrderResource extends Resource
{
    protected static ?string $navigationLabel = 'List Order';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

                Forms\Components\TextInput::make('phone')
                ->default('') 
                ->required(),

                Forms\Components\TextInput::make('email')
                ->required(),

                Forms\Components\Select::make('plan_id')
                ->relationship('plan', 'name')
                ->required(),

                Forms\Components\Select::make('payment_id')
                ->relationship('payment', 'provider')
                ->required(),

                Forms\Components\Select::make('payment_method')
                ->relationship('payment', 'payment_method')
                ->required()
                ->label('Metode Pembayaran'),

                Forms\Components\Select::make('amount')
                ->relationship('plan', 'price')
                ->required()
                ->label('Harga'),

                Forms\Components\Select::make('payment_amount')
                ->relationship('plan', 'price')
                ->required()
                ->label('Total Bayar'),

                Forms\Components\Select::make('status')
                ->options(
                    array_combine(
                        array_map(fn($status) => $status->value, Status::cases()), 
                        array_map(fn($status) => $status->getLabel(), Status::cases())
                    )
                )
                ->default(Status::Pending->value)
                ->label('Order Status')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('payment.provider')
                    ->label('Provider Payment')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('payment.payment_method')
                    ->label('Metode Pembayaran'),

                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan'),

                Tables\Columns\TextColumn::make('plan.price')
                    ->label('Harga'),

                Tables\Columns\TextColumn::make('plan.price')
                    ->label('Total Bayar'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('expired_plan')
                    ->label('Expired Plan'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'unpaid' => 'Unpaid',
                    'paid' => 'Paid',
                    'cancel' => 'Cancel',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
