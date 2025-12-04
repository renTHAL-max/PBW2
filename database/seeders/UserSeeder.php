<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@rental.com',
            'password' => Hash::make('password'),
        ]);
        $owner->roles()->attach(Role::where('name', 'owner')->first()->id);

        $kasir = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@rental.com',
            'password' => Hash::make('password'),
        ]);
        $kasir->roles()->attach(Role::where('name', 'kasir')->first()->id);
    }
}