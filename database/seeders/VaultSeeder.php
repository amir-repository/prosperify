<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vaults')->insert(
            ['name' => 'FoodBank HQ', 'city_id' => 1, 'address' => 'Jalan kemerdekaan No. 17']
        );
        DB::table('vaults')->insert(
            ['name' => 'Backup HQ', 'city_id' => 1, 'address' => 'Jalan Bersama No. 2']
        );
    }
}
