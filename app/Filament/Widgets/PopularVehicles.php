<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularVehicles extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Vehicle::query()
                    ->withCount('rentalItems')
                    ->orderBy('rental_items_count', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->label('Merek')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('model')
                    ->label('Model'),
                
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Plat Nomor'),
                
                Tables\Columns\TextColumn::make('rental_items_count')
                    ->label('Total Disewa')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'tersedia',
                        'warning' => 'sedang_disewa',
                        'danger' => 'dalam_perawatan',
                    ]),
            ])
            ->heading('Top 5 Kendaraan Terpopuler');
    }
}