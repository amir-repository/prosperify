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

        // public const PLANNED = 1;
        // public const ASSIGNED = 2;
        // public const LAUNCHED = 3;
        // public const INCOMPLETED = 4;
        // public const COMPLETED = 5;
        // public const CANCELED = 6;

        DB::table('donation_statuses')->insert([
            'name' => 'planned',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'assigned',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'launched',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'incompleted',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'completed',
        ]);

        DB::table('donation_statuses')->insert([
            'name' => 'canceled',
        ]);
    }
}
