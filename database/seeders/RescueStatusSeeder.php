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
            'name' => 'direncanakan',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'diajukan',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'diproses',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'diambil',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'disimpan',
        ]);

        DB::table('rescue_statuses')->insert([
            'name' => 'dibatalkan',
        ]);
    }
}
