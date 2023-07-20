<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => fake()->name,
            'cell' => fake()->phoneNumber,
            'sex' => fake()->word,
            'vip' => fake()->boolean,
            'type' => fake()->word,
            'risk_level' => fake()->numberBetween(25, 100),
            'ref' => fake()->name,
            'dob' => fake()->date,
            'secondary_phone' => fake()->phoneNumber,
        ];
    }
}
