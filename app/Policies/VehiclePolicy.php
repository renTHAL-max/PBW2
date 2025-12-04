<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa lihat
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner'); // Hanya owner yang bisa tambah kendaraan
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('owner'); // Hanya owner yang bisa edit
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('owner'); // Hanya owner yang bisa hapus
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('owner');
    }
}