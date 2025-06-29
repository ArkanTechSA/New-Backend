<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@ymtaz.test',
        ], [
            'first_name' => 'Admin',
            'latest_name' => 'User',
            'full_name' => 'Admin User',
            'email' => 'ib.farag@gmail.com',
            'password' => Hash::make('01001802203'),
            'role' => 3,
            'is_old' => false,
        ]);
    }
}
