<?php

namespace Database\Seeders;

use App\Models\Rescue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RescueStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rescue_statuses')->insert([
            'name' => 'planned',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'submitted',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'processed',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'assigned',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'incompleted',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'completed',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'rejected',
        ]);
    }
}
