<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalResource\Pages;
use App\Models\Rental;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Transaksi Rental';
    
    protected static ?string $navigationGroup = 'Transaksi';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan & Tanggal')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('phone')
                                    ->required(),
                                Forms\Components\TextInput::make('id_card_number')
                                    ->required(),
                                Forms\Components\Textarea::make('address')
                                    ->required(),
                            ])
                            ->columnSpan(2),
                        
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now())
                            ->minDate(now())
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $endDate = $get('end_date');
                                if ($state && $endDate) {
                                    $days = Carbon::parse($state)->diffInDays(Carbon::parse($endDate)) + 1;
                                    $set('duration_days', $days);
                                    static::recalculateTotal($get, $set);
                                }
                            })
                            ->columnSpan(1),
                        
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->minDate(fn (Get $get) => $get('start_date') ?: now())
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $startDate = $get('start_date');
                                if ($startDate && $state) {
                                    $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($state)) + 1;
                                    $set('duration_days', $days);
                                    static::recalculateTotal($get, $set);
                                }
                            })
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('duration_days')
                            ->label('Durasi (Hari)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(1)
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Aktif',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('pending')
                            ->columnSpan(1),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Kendaraan yang Disewa')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Item Rental')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('vehicle_id')
                                    ->label('Kendaraan')
                                    ->options(function (Get $get) {
                                        $startDate = $get('../../start_date');
                                        $endDate = $get('../../end_date');
                                        
                                        if (!$startDate || !$endDate) {
                                            return Vehicle::where('status', 'tersedia')
                                                ->get()
                                                ->pluck('full_name', 'id');
                                        }
                                        
                                        return Vehicle::where('status', 'tersedia')
                                            ->get()
                                            ->filter(function ($vehicle) use ($startDate, $endDate) {
                                                return $vehicle->isAvailableForRent($startDate, $endDate);
                                            })
                                            ->mapWithKeys(fn ($v) => [$v->id => $v->brand . ' ' . $v->model . ' - ' . $v->license_plate]);
                                    })
                                    ->required()
                                    ->searchable()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($state) {
                                            $vehicle = Vehicle::find($state);
                                            $set('price_per_day', $vehicle->price_per_day);
                                            
                                            $days = $get('../../duration_days') ?? 1;
                                            $set('days', $days);
                                            $set('subtotal', $vehicle->price_per_day * $days);
                                            
                                            // Recalculate parent total
                                            $items = $get('../../items');
                                            $subtotal = collect($items)->sum('subtotal');
                                            $set('../../subtotal', $subtotal);
                                            $set('../../total_amount', $subtotal);
                                        }
                                    })
                                    ->columnSpan(3),
                                
                                Forms\Components\TextInput::make('price_per_day')
                                    ->label('Harga/Hari')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                                
                                Forms\Components\TextInput::make('days')
                                    ->label('Hari')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                            ])
                            ->columns(8)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                static::recalculateTotal($get, $set);
                            })
                            ->deleteAction(
                                fn ($action) => $action->after(function (Get $get, Set $set) {
                                    static::recalculateTotal($get, $set);
                                })
                            )
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Kendaraan')
                            ->reorderable(false)
                            ->collapsible()
                            ->cloneable(),
                    ]),

                Forms\Components\Section::make('Ringkasan Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('late_fee')
                            ->label('Denda Keterlambatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Pembayaran')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => 'Belum Dibayar',
                                'dp' => 'DP',
                                'paid' => 'Lunas',
                            ])
                            ->required()
                            ->default('unpaid')
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Pengembalian')
                    ->schema([
                        Forms\Components\DatePicker::make('actual_return_date')
                            ->label('Tanggal Pengembalian Aktual')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $endDate = $get('end_date');
                                $subtotal = $get('subtotal') ?? 0;
                                $durationDays = $get('duration_days') ?? 1;
                                
                                if ($state && $endDate && $subtotal > 0) {
                                    $actualDate = Carbon::parse($state);
                                    $plannedDate = Carbon::parse($endDate);
                                    
                                    if ($actualDate->greaterThan($plannedDate)) {
                                        $lateDays = $actualDate->diffInDays($plannedDate);
                                        $dailyRate = $subtotal / $durationDays;
                                        $lateFee = $lateDays * $dailyRate * 0.2; // 20% denda per hari
                                        
                                        $set('late_fee', $lateFee);
                                        $set('total_amount', $subtotal + $lateFee);
                                    } else {
                                        $set('late_fee', 0);
                                        $set('total_amount', $subtotal);
                                    }
                                }
                            })
                            ->helperText('Isi jika kendaraan sudah dikembalikan'),
                    ])
                    ->visible(fn (Get $get) => $get('status') === 'completed')
                    ->collapsed(),
            ]);
    }

    protected static function recalculateTotal(Get $get, Set $set): void
    {
        $items = collect($get('items'));
        $subtotal = $items->sum('subtotal');
        
        $set('subtotal', $subtotal);
        $set('total_amount', $subtotal + ($get('late_fee') ?? 0));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rental_code')
                    ->label('Kode Rental')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                
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
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'active',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'dp',
                        'success' => 'paid',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid' => 'Belum Dibayar',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        Forms\Components\DatePicker::make('start_date_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('start_date_to')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date_from'], fn ($q, $date) => $q->whereDate('start_date', '>=', $date))
                            ->when($data['start_date_to'], fn ($q, $date) => $q->whereDate('start_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('complete')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Rental $record) {
                        $record->update(['status' => 'completed']);
                        
                        // Update vehicle status kembali ke tersedia
                        foreach ($record->items as $item) {
                            $item->vehicle->update(['status' => 'tersedia']);
                        }
                    })
                    ->visible(fn (Rental $record) => $record->status === 'active'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }
}