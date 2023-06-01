<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // admin seed
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'type' => 'admin'
        ]);

        // volunteer seed
        DB::table('users')->insert([
            'name' => 'Volunteer Amir',
            'email' => 'amir@mail.com',
            'password' => Hash::make('password'),
            'type' => 'volunteer'
        ]);

        DB::table('users')->insert([
            'name' => 'Volunteer Adip',
            'email' => 'adip@mail.com',
            'password' => Hash::make('password'),
            'type' => 'volunteer'
        ]);

        // donor seed
        DB::table('users')->insert([
            'name' => 'Donor Budi',
            'email' => 'budi@mail.com',
            'password' => Hash::make('password'),
            'type' => 'donor'
        ]);

        DB::table('users')->insert([
            'name' => 'Donor Neo',
            'email' => 'neo@mail.com',
            'password' => Hash::make('password'),
            'type' => 'donor'
        ]);
    }
}
