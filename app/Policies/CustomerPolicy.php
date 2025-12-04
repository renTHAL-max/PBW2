<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $customer): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true; // Semua user bisa tambah pelanggan
    }

    public function update(User $user, Customer $customer): bool
    {
        return true; // Semua user bisa edit pelanggan
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasRole('owner'); // Hanya owner yang bisa hapus
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('owner');
    }
}