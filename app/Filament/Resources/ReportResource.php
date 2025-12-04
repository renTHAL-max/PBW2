<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Rental;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class ReportResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    protected static ?string $navigationLabel = 'Laporan';
    
    protected static ?string $navigationGroup = 'Laporan';
    
    protected static ?string $slug = 'laporan';
    
    protected static ?string $modelLabel = 'Laporan';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Rental::query()->whereIn('status', ['completed', 'active']))
            ->columns([
                Tables\Columns\TextColumn::make('rental_code')
                    ->label('Kode Rental')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dilayani Oleh')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Durasi')
                    ->suffix(' hari')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Kendaraan')
                    ->counts('items')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR'),
                
                Tables\Columns\TextColumn::make('late_fee')
                    ->label('Denda')
                    ->money('IDR')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->weight('bold')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'active',
                        'success' => 'completed',
                    ]),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'dp',
                        'success' => 'paid',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
                    ])
                    ->multiple(),
                
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                 
                        if ($data['from'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['from'])->toFormattedDateString();
                        }
                 
                        if ($data['until'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['until'])->toFormattedDateString();
                        }
                 
                        return $indicators;
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (array $data, $livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        $rentals = $query->with(['customer', 'items.vehicle', 'user'])->get();
                        
                        $pdf = Pdf::loadView('reports.rental-pdf', [
                            'rentals' => $rentals,
                            'total' => $rentals->sum('total_amount'),
                            'dateFrom' => request('tableFilters.date_range.from'),
                            'dateUntil' => request('tableFilters.date_range.until'),
                        ]);
                        
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'laporan-rental-' . now()->format('Y-m-d') . '.pdf');
                    }),
                
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        $rentals = $query->with(['customer', 'items.vehicle', 'user'])->get();
                        
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => 'attachment; filename="laporan-rental-' . now()->format('Y-m-d') . '.csv"',
                        ];
                        
                        $callback = function () use ($rentals) {
                            $file = fopen('php://output', 'w');
                            
                            // Header
                            fputcsv($file, [
                                'Kode Rental',
                                'Pelanggan',
                                'Dilayani Oleh',
                                'Tanggal Mulai',
                                'Tanggal Selesai',
                                'Durasi (Hari)',
                                'Subtotal',
                                'Denda',
                                'Total',
                                'Status',
                                'Pembayaran',
                            ]);
                            
                            // Data
                            foreach ($rentals as $rental) {
                                fputcsv($file, [
                                    $rental->rental_code,
                                    $rental->customer->name,
                                    $rental->user->name,
                                    $rental->start_date->format('Y-m-d'),
                                    $rental->end_date->format('Y-m-d'),
                                    $rental->duration_days,
                                    $rental->subtotal,
                                    $rental->late_fee,
                                    $rental->total_amount,
                                    $rental->status,
                                    $rental->payment_status,
                                ]);
                            }
                            
                            fclose($file);
                        };
                        
                        return response()->stream($callback, 200, $headers);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}