<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationFoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('donation_food_statuses')->insert([
            'name' => 'PLANNED',
        ]);

        DB::table('donation_food_statuses')->insert([
            'name' => 'LAUNCHED',
        ]);

        DB::table('donation_food_statuses')->insert([
            'name' => 'TAKEN',
        ]);

        DB::table('donation_food_statuses')->insert([
            'name' => 'DELIVERED',
        ]);

        DB::table('donation_food_statuses')->insert([
            'name' => 'GIVEN',
        ]);

        DB::table('donation_food_statuses')->insert([
            'name' => 'FAILED',
        ]);
    }
}
