<?php

namespace App\Filament\Resources\MaintenanceRecordResource\Pages;

use App\Filament\Resources\MaintenanceRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceRecord extends CreateRecord
{
    protected static string $resource = MaintenanceRecordResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->status === 'in_progress') {
            $record->vehicle->update(['status' => 'dalam_perawatan']);
        } elseif (in_array($record->status, ['completed', 'cancelled'])) {
            $record->vehicle->update(['status' => 'tersedia']);
        }
    }
}