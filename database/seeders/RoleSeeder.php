<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'owner',
            'description' => 'Pemilik bisnis dengan akses penuh',
        ]);

        Role::create([
            'name' => 'kasir',
            'description' => 'Kasir/Admin yang menangani transaksi',
        ]);
    }
}