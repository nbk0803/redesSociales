<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GroupMember>
 */
class GroupMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->create()->id,
            'group_id' => Group::query()->inRandomOrder()->value('id')
                ?? Group::factory()->create()->id,
            'role' => $this->faker->randomElement(['member', 'moderator', 'admin']),
            'status' => 'active',
            'registered_by' => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->create()->id,
            'joined_at' => now(),
        ];
    }
}
