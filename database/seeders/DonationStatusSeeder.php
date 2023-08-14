<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('donation_statuses')->insert([
            'name' => 'PLANNED',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'LAUNCHED',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'INCOMPLETE',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'COMPLETE',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'FAILED',
        ]);
    }
}
