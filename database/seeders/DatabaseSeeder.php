<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(5)->create();
        \App\Models\User::factory()->create([
             'name' => 'Admin',
             'username' => 'admin',
             'email' => 'rexarvind@gmail.com',
         ]);

        \App\Models\Project::factory(10)->create();
    }
}
