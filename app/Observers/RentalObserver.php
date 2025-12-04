<?php

namespace App\Observers;

use App\Models\Rental;

class RentalObserver
{
    public function created(Rental $rental): void
    {
        // Generate kode rental otomatis sudah di model
        // Set user_id ke user yang login
        if (!$rental->user_id) {
            $rental->user_id = auth()->id();
            $rental->save();
        }
    }

    public function updated(Rental $rental): void
    {
        // Jika status berubah menjadi active, update status kendaraan
        if ($rental->isDirty('status')) {
            if ($rental->status === 'active') {
                foreach ($rental->items as $item) {
                    $item->vehicle->update(['status' => 'sedang_disewa']);
                }
            } elseif (in_array($rental->status, ['completed', 'cancelled'])) {
                foreach ($rental->items as $item) {
                    $item->vehicle->update(['status' => 'tersedia']);
                }
            }
        }

        // Jika actual_return_date diisi, hitung denda otomatis
        if ($rental->isDirty('actual_return_date') && $rental->actual_return_date) {
            $lateFee = $rental->calculateLateFee();
            if ($lateFee > 0) {
                $rental->late_fee = $lateFee;
                $rental->total_amount = $rental->subtotal + $lateFee;
                $rental->saveQuietly(); // Prevent infinite loop
            }
        }
    }

    public function deleting(Rental $rental): void
    {
        // Kembalikan status kendaraan ke tersedia jika rental dihapus
        foreach ($rental->items as $item) {
            if ($item->vehicle->status === 'sedang_disewa') {
                $item->vehicle->update(['status' => 'tersedia']);
            }
        }
    }
}