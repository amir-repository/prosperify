<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodRescueStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('food_rescue_statuses')->insert([
            'name' => 'planned',
        ]);

        DB::table('food_rescue_statuses')->insert([
            'name' => 'submitted',
        ]);

        DB::table('food_rescue_statuses')->insert([
            'name' => 'processed',
        ]);

        DB::table('food_rescue_statuses')->insert([
            'name' => 'assigned',
        ]);

        DB::table('food_rescue_statuses')->insert([
            'name' => 'taken',
        ]);

        DB::table('food_rescue_statuses')->insert([
            'name' => 'stored',
        ]);

        DB::table('food_rescue_statuses')->insert([
            'name' => 'rejected',
        ]);
    }
}
