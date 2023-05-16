<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractCycle>
 */
class ContractCycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => fake()->numberBetween(5000,200000),
            'premium' => fake()->numberBetween(1000, 20000),
            'notes' => fake()->paragraph(),
            'vendor' => fake()->word,
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'contract_id' => Contract::factory()
        ];
    }
}
