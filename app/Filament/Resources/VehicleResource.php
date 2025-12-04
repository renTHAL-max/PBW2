<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    
    protected static ?string $navigationLabel = 'Kendaraan';
    
    protected static ?string $navigationGroup = 'Data Master';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kendaraan')
                    ->schema([
                        Forms\Components\Select::make('vehicle_category_id')
                            ->label('Kategori Kendaraan')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->rows(3),
                            ]),
                        
                        Forms\Components\TextInput::make('license_plate')
                            ->label('Nomor Plat')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('B 1234 XYZ')
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'tersedia' => 'Tersedia',
                                'sedang_disewa' => 'Sedang Disewa',
                                'dalam_perawatan' => 'Dalam Perawatan',
                            ])
                            ->required()
                            ->default('tersedia')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Kendaraan')
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Merek')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Toyota'),
                        
                        Forms\Components\TextInput::make('model')
                            ->label('Model')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Avanza'),
                        
                        Forms\Components\TextInput::make('year')
                            ->label('Tahun')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 1)
                            ->placeholder(date('Y')),
                        
                        Forms\Components\TextInput::make('color')
                            ->label('Warna')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Hitam'),
                        
                        Forms\Components\TextInput::make('seat_capacity')
                            ->label('Kapasitas Penumpang')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(50)
                            ->placeholder('7'),
                        
                        Forms\Components\Select::make('transmission')
                            ->label('Transmisi')
                            ->options([
                                'manual' => 'Manual',
                                'automatic' => 'Automatic',
                            ])
                            ->placeholder('Pilih Transmisi'),
                        
                        Forms\Components\Select::make('fuel_type')
                            ->label('Jenis Bahan Bakar')
                            ->options([
                                'bensin' => 'Bensin',
                                'diesel' => 'Diesel',
                                'electric' => 'Listrik',
                                'hybrid' => 'Hybrid',
                            ])
                            ->placeholder('Pilih Bahan Bakar'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Harga & Deskripsi')
                    ->schema([
                        Forms\Components\TextInput::make('price_per_day')
                            ->label('Harga per Hari')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->placeholder('350000')
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Deskripsi kondisi kendaraan...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Foto Kendaraan')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('vehicle_images')
                            ->label('Upload Foto')
                            ->collection('vehicle_images')
                            ->multiple()
                            ->maxFiles(5)
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText('Upload maksimal 5 foto. Format: JPG, PNG. Maksimal 2MB per file.')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('vehicle_images')
                    ->label('Foto')
                    ->collection('vehicle_images')
                    ->circular()
                    ->stacked()
                    ->limit(1),
                
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Plat Nomor')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('brand')
                    ->label('Merek')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'tersedia',
                        'warning' => 'sedang_disewa',
                        'danger' => 'dalam_perawatan',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tersedia' => 'Tersedia',
                        'sedang_disewa' => 'Sedang Disewa',
                        'dalam_perawatan' => 'Dalam Perawatan',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                
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
                        'tersedia' => 'Tersedia',
                        'sedang_disewa' => 'Sedang Disewa',
                        'dalam_perawatan' => 'Dalam Perawatan',
                    ]),
                
                Tables\Filters\SelectFilter::make('vehicle_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable(),
                
                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('price_from')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price_to')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['price_from'], fn ($q, $price) => $q->where('price_per_day', '>=', $price))
                            ->when($data['price_to'], fn ($q, $price) => $q->where('price_per_day', '<=', $price));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}