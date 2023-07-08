<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = collect([
            'surabaya',
            'bandung'
        ]);

        $cities->each(function ($city) {
            DB::table('cities')->insert(
                ['name' => $city],
            );
        });
    }
}
