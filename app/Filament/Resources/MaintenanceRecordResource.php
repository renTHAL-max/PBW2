<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceRecordResource\Pages;
use App\Models\MaintenanceRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MaintenanceRecordResource extends Resource
{
    protected static ?string $model = MaintenanceRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    
    protected static ?string $navigationLabel = 'Maintenance Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'license_plate')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->brand} {$record->model} ({$record->license_plate})")
                    ->searchable(['brand', 'model', 'license_plate'])
                    ->preload()
                    ->required(),
                    
                Forms\Components\DatePicker::make('maintenance_date')
                    ->label('Maintenance Date')
                    ->required()
                    ->default(now())
                    ->native(false),
                    
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'service' => 'Service',
                        'repair' => 'Repair',
                        'inspection' => 'Inspection',
                        'oil_change' => 'Oil Change',
                        'tire_change' => 'Tire Change',
                        'brake_service' => 'Brake Service',
                        'battery_replacement' => 'Battery Replacement',
                        'transmission_service' => 'Transmission Service',
                        'engine_repair' => 'Engine Repair',
                        'other' => 'Other',
                    ])
                    ->searchable()
                    ->required(),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('cost')
                    ->label('Cost')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->inputMode('decimal')
                    ->step(0.01)
                    ->minValue(0),
                    
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.license_plate')
                    ->label('License Plate')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('vehicle.brand')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('vehicle.model')
                    ->label('Model')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'info',
                        'repair' => 'warning',
                        'inspection' => 'success',
                        'oil_change' => 'gray',
                        default => 'primary',
                    })
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('cost')
                    ->label('Cost')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'service' => 'Service',
                        'repair' => 'Repair',
                        'inspection' => 'Inspection',
                        'oil_change' => 'Oil Change',
                        'tire_change' => 'Tire Change',
                        'brake_service' => 'Brake Service',
                        'battery_replacement' => 'Battery Replacement',
                        'transmission_service' => 'Transmission Service',
                        'engine_repair' => 'Engine Repair',
                        'other' => 'Other',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                    
                Tables\Filters\Filter::make('maintenance_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('maintenance_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('maintenance_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                // PERBAIKAN: Logika Update Status saat Hapus Satu Data
                Tables\Actions\DeleteAction::make()
                    ->after(function ($record) {
                        if ($record->vehicle) {
                            $record->vehicle->update(['status' => 'tersedia']);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // PERBAIKAN: Logika Update Status saat Hapus Banyak Data
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function (Collection $records) {
                            foreach ($records as $record) {
                                if ($record->vehicle) {
                                    $record->vehicle->update(['status' => 'tersedia']);
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('maintenance_date', 'desc');
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
            'index' => Pages\ListMaintenanceRecords::route('/'),
            'create' => Pages\CreateMaintenanceRecord::route('/create'),
            'edit' => Pages\EditMaintenanceRecord::route('/{record}/edit'),
        ];
    }
}