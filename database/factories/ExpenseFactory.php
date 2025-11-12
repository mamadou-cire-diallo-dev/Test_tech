<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id' => User::factory(), // Assign a user by default
            'title' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'currency' => 'EUR',
            'spent_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'category' => $this->faker->randomElement(['MEAL', 'TRAVEL', 'HOTEL', 'OTHER']),
            'receipt_path' => null,
            'status' => 'DRAFT', // Default status
        ];
    }
}
