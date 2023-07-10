<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Recipient;
use App\Models\RescueStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

        $this->call(
            [
                RoleAndPermissionSeeder::class,
                UserSeeder::class,
                PointSeeder::class,
                RescueStatusSeeder::class,
                UnitSeeder::class,
                CategorySeeder::class,
                SubCategorySeeder::class,
                CitySeeder::class,
                VaultSeeder::class,
                RecipientSeeder::class
            ]
        );

        // // recipient seed
        // DB::table('recipients')->insert([
        //     [
        //         'name' => 'Agus',
        //         'address' => 'Jalan bersama',
        //         'phone' => '08123456789',
        //         'family_members' => 4,
        //         'document' => '',
        //         'status' => 'diterima'
        //     ]
        // ]);
    }
}
