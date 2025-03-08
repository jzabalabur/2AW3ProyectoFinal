<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Jon',
            'email' => 'jon@admin.com',
            'password' => Hash::make('jon'),
        ]);

        // Create the second user
        User::create([
            'name' => 'Pablo',
            'email' => 'pablo@admin.com',
            'password' => Hash::make('pablo'),
        ]);

        User::create([
            'name' => 'Jon',
            'email' => 'jon@user.com',
            'password' => Hash::make('jon'),
        ]);

        // Create the second user
        User::create([
            'name' => 'Pablo',
            'email' => 'pablo@user.com',
            'password' => Hash::make('pablo'),
        ]);
    }
}
