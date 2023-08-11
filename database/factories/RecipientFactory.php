<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipient>
 */
class RecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => fake()->phoneNumber(),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'family_members' => rand(1, 5),
            'photo' => fake()->name(),
            'recipient_status_id' => 3
        ];
    }
}
