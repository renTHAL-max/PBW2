<?php

// app/Filament/Widgets/LatestRentals.php
namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestRentals extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rental::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('rental_code')
                    ->label('Kode')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan'),
                
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date(),
                
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date(),
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'active',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->heading('Transaksi Terbaru');
    }
}