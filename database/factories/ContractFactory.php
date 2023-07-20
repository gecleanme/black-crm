<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Customer::factory(),
            'type' => fake()->word,
            'make' => fake()->word,
            'notes' => fake()->paragraph,
            'prod_year' => fake()->numberBetween(1999, 2024),
            'model' => fake()->word,
            'regno' => fake()->word,
            'pnum' => fake()->word,
            'lic_exp' => fake()->date,
            'title' => fake()->word,

        ];
    }
}
