<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(10),
            'privacy' => $this->faker->randomElement(['public', 'private']),
            'creator_id' => \App\Models\User::factory(),
            'registered_by' => \App\Models\User::factory(),
            'status' => 'active',
        ];
    }
}
