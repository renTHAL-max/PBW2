<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Rental;

class RentalPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa lihat transaksi
    }

    public function view(User $user, Rental $rental): bool
    {
        // Owner bisa lihat semua, kasir hanya lihat miliknya
        return $user->hasRole('owner') || $rental->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true; // Semua user bisa buat transaksi
    }

    public function update(User $user, Rental $rental): bool
    {
        // Owner bisa edit semua, kasir hanya edit miliknya yang masih pending
        if ($user->hasRole('owner')) {
            return true;
        }
        
        return $rental->user_id === $user->id && $rental->status === 'pending';
    }

    public function delete(User $user, Rental $rental): bool
    {
        return $user->hasRole('owner'); // Hanya owner yang bisa hapus
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('owner');
    }
}