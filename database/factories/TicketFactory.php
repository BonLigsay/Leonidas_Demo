<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['open', 'closed', 'in_progress']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'user_id' => User::inRandomOrder()->first()->id, // Assign a random user
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
