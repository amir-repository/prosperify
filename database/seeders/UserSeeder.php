<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = collect([
            [
                'name' => 'Admin Ivan',
                'email' => 'ivan@mail.com',
                'phone' => '081234561111',
                'address' => 'Jalan Bhayangkara, Kota Tarakan'
            ],
        ]);

        $admins->each(function ($admin) {
            $admin = $this->createUser($admin);
            $admin->assignRole('admin');
        });

        $volunteers = collect([
            [
                'name' => 'Volunteer Adip',
                'email' => 'adip@mail.com',
                'phone' => '081234567888',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
            [
                'name' => 'Volunteer Amir',
                'email' => 'amir@mail.com',
                'phone' => '081234567777',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
            [
                'name' => 'Volunteer bagas',
                'email' => 'bagas@mail.com',
                'phone' => '081234567717',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
            [
                'name' => 'Volunteer Nisa',
                'email' => 'nisa@mail.com',
                'phone' => '081234560777',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
            [
                'name' => 'Volunteer Alif',
                'email' => 'alif@mail.com',
                'phone' => '081234567767',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
            [
                'name' => 'Volunteer Rudi',
                'email' => 'rudi@mail.com',
                'phone' => '081234567877',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
            [
                'name' => 'Volunteer Halin',
                'email' => 'halin@mail.com',
                'phone' => '081232567777',
                'address' => 'Jalan Mulawarman, Kota Tarakan'
            ],
        ]);

        $volunteers->each(function ($volunteer) {
            $volunteer = $this->createUser($volunteer);
            $volunteer->assignRole('volunteer');
        });

        $donors = collect([
            [
                'name' => 'Donor Budi',
                'email' => 'budi@mail.com',
                'phone' => '081234567890',
                'address' => 'Jalan bersama III, Kota Tarakan'
            ],
            [
                'name' => 'Donor Neo',
                'email' => 'neo@mail.com',
                'phone' => '081234567899',
                'address' => 'Jalan bersama III, Kota Tarakan'

            ],
        ]);

        $donors->each(function ($donor) {
            $donor = $this->createUser($donor);
            $donor->assignRole('donor');
        });
    }

    private function createUser($user)
    {
        return \App\Models\User::factory()->create([
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'address' => $user['address'],
        ]);
    }
}
