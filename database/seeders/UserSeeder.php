<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create alex user as admin
        \App\Models\User::factory()->create([
            'name' => 'Alex Watson',
            'email' => 'alex@admin.com',
            'password' => Hash::make('password'),
        ]);

        // create 10 users
        \App\Models\User::factory(10)
            ->create();
    }
}
