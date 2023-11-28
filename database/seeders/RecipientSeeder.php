<?php

namespace Database\Seeders;

use App\Models\Recipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recipients')->insert([
            'nik' => "1234567890123456",
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'family_members' => rand(1, 5),
            'photo' => fake()->name(),
            'recipient_status_id' => 2
        ]);

        DB::table('recipients')->insert([
            'nik' => "1234567890123457",
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'family_members' => rand(1, 5),
            'photo' => fake()->name(),
            'recipient_status_id' => 2
        ]);

        DB::table('recipients')->insert([
            'nik' => "1234567890123458",
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'family_members' => rand(1, 5),
            'photo' => fake()->name(),
            'recipient_status_id' => 2
        ]);

        DB::table('recipients')->insert([
            'nik' => "1234567890123459",
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'family_members' => rand(1, 5),
            'photo' => fake()->name(),
            'recipient_status_id' => 2
        ]);

        DB::table('recipients')->insert([
            'nik' => "1234567890123452",
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'family_members' => rand(1, 5),
            'photo' => fake()->name(),
            'recipient_status_id' => 2
        ]);
    }
}
