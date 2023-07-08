<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            'perishable',
            'non perishable'
        ]);

        $categories->each(function ($category) {
            DB::table('categories')->insert(
                ['name' => $category]
            );
        });
    }
}
