<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', env('ADMIN_EMAIL', 'admin@basketcore.xyz'))->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => env('ADMIN_EMAIL', 'admin@basketcore.xyz'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'changeme123')),
                'role' => 'admin',
            ]);
        }
    }
}
