<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Pelanggan';
    
    protected static ?string $navigationGroup = 'Data Master';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('John Doe')
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('john@example.com')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('08123456789')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('id_card_number')
                            ->label('No. KTP')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('3201234567890001')
                            ->columnSpan(1),
                        
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->maxDate(now())
                            ->placeholder('Pilih tanggal')
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Jl. Contoh No. 123, Jakarta'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dokumen')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('id_card')
                            ->label('Upload Foto KTP')
                            ->collection('id_card')
                            ->image()
                            ->maxSize(2048)
                            ->helperText('Format: JPG, PNG. Maksimal 2MB.')
                            ->columnSpan(1),
                        
                        SpatieMediaLibraryFileUpload::make('driver_license')
                            ->label('Upload Foto SIM')
                            ->collection('driver_license')
                            ->image()
                            ->maxSize(2048)
                            ->helperText('Format: JPG, PNG. Maksimal 2MB.')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                
                Tables\Columns\TextColumn::make('id_card_number')
                    ->label('No. KTP')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('has_documents')
                    ->label('Dokumen')
                    ->boolean()
                    ->state(function (Customer $record): bool {
                        return $record->getMedia('id_card')->isNotEmpty() && 
                               $record->getMedia('driver_license')->isNotEmpty();
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('rentals_count')
                    ->label('Total Rental')
                    ->counts('rentals')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_documents')
                    ->label('Memiliki Dokumen Lengkap')
                    ->query(function ($query) {
                        return $query->whereHas('media', function ($q) {
                            $q->where('collection_name', 'id_card');
                        })->whereHas('media', function ($q) {
                            $q->where('collection_name', 'driver_license');
                        });
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}