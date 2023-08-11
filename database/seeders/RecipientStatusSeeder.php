<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipientStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = collect(['submitted', 'accepted', 'rejected', 'propered']);

        $statuses->each(function ($status) {
            DB::table('recipient_statuses')->insert([
                'name' => $status
            ]);
        });
    }
}
