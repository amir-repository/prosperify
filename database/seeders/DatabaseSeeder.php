<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DonationFood;
use App\Models\DonationStatus;
use App\Models\FoodDonationStatus;
use App\Models\Recipient;
use App\Models\RecipientStatus;
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
        $this->call(
            [
                RoleAndPermissionSeeder::class,
                UserSeeder::class,
                PointSeeder::class,
                RescueStatusSeeder::class,
                FoodRescueStatusSeeder::class,
                UnitSeeder::class,
                CategorySeeder::class,
                SubCategorySeeder::class,
                CitySeeder::class,
                VaultSeeder::class,
                RecipientStatusSeeder::class,
                RecipientSeeder::class,
                DonationStatusSeeder::class,
                FoodDonationStatusSeeder::class
            ]
        );
    }
}
