<?php

namespace App\Filament\Resources\MaintenanceRecordResource\Pages;

use App\Filament\Resources\MaintenanceRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\MaintenanceRecord;

class EditMaintenanceRecord extends EditRecord
{
    protected static string $resource = MaintenanceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function ($record) {
                    if ($record->vehicle) {
                        $record->vehicle->update(['status' => 'tersedia']);
                    }
                }),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        if ($record->status === 'in_progress') {
            $record->vehicle->update(['status' => 'dalam_perawatan']);
        } elseif (in_array($record->status, ['completed', 'cancelled'])) {
            $record->vehicle->update(['status' => 'tersedia']);
        }
    }
}