<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodDonationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('food_donation_statuses')->insert([
            'name' => 'planned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'adjusted_after_planned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'assigned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'adjusted_after_assigned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'taken',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'adjusted_after_taken',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'given',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'canceled',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'trashed',
        ]);
    }
}
