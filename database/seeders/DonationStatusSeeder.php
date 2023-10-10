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

    public const PLANNED = 1;
    public const ASSIGNED = 2;
    public const INCOMPLETED = 3;
    public const COMPLETED = 4;
    public const FAILED = 5;

    public function run(): void
    {
        DB::table('donation_statuses')->insert([
            'name' => 'planned',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'assigned',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'incompleted',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'completed',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'failed',
        ]);
    }
}
