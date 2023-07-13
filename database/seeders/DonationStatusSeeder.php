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
            'name' => 'direncanakan',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'dilaksanakan',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'diantar',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'diserahkan',
        ]);
    }
}
