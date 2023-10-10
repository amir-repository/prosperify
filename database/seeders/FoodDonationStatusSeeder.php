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
            'name' => 'adjusted after planned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'assigned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'adjusted after assigned',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'launched',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'adjusted after launched',
        ]);

        DB::table('food_donation_statuses')->insert([
            'name' => 'delivered',
        ]);

        // untuk makanan yang rusak di jalan akan dibuang
        DB::table('food_donation_statuses')->insert([
            'name' => 'trashed',
        ]);
    }
}
