<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // admin seed
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'type' => 'admin'
        ]);

        // volunteer seed
        DB::table('users')->insert([
            'name' => 'Volunteer Amir',
            'email' => 'amir@mail.com',
            'password' => Hash::make('password'),
            'type' => 'volunteer'
        ]);

        DB::table('users')->insert([
            'name' => 'Volunteer Adip',
            'email' => 'adip@mail.com',
            'password' => Hash::make('password'),
            'type' => 'volunteer'
        ]);

        // donor seed
        DB::table('users')->insert([
            'name' => 'Donor Budi',
            'email' => 'budi@mail.com',
            'password' => Hash::make('password'),
            'type' => 'donor'
        ]);

        DB::table('users')->insert([
            'name' => 'Donor Neo',
            'email' => 'neo@mail.com',
            'password' => Hash::make('password'),
            'type' => 'donor'
        ]);

        // city seed
        DB::table('cities')->insert(
            ['name' => 'surabaya'],
        );

        DB::table('cities')->insert(
            ['name' => 'bandung'],
        );

        // vaults seed
        DB::table('vaults')->insert(
            ['name' => 'FoodBank HQ', 'city_id' => 1, 'address' => 'Jalan kemerdekaan No. 17']
        );

        // category seed
        DB::table('categories')->insert(
            ['name' => 'perishable']
        );

        DB::table('categories')->insert(
            ['name' => 'non perishable']
        );

        // sub-categories seed
        DB::table('sub_categories')->insert(
            ['name' => 'pastry', 'category_id' => 1]
        );

        DB::table('sub_categories')->insert(
            ['name' => 'protein hewani', 'category_id' => 1]
        );

        DB::table('sub_categories')->insert(
            ['name' => 'karbo/nasi', 'category_id' => 1]
        );

        DB::table('sub_categories')->insert(
            ['name' => 'sayur', 'category_id' => 1]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'beras', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'mie', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'protein hewani kalengan', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'susu', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'bumbu dapur', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'kopi', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'teh', 'category_id' => 2]
        );
        DB::table('sub_categories')->insert(
            ['name' => 'minuman lainnya', 'category_id' => 2]
        );
    }
}
