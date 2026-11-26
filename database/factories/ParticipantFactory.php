<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\Tours;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tour_id' => Tours::inRandomOrder()->value('id'),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'persons' => fake()->numberBetween(1, 4),
        ];
    }
}
