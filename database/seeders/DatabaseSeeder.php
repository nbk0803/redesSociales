<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $email = 'test@example.com';

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ]
        );

        // Crear 50 usuarios adicionales solo si la BD esta practicamente vacia
        if (User::count() <= 1) {
            User::factory(50)->create();
        }

        if (Group::count() === 0) {
            // Crear 10 grupos y asignar miembros aleatorios desde los usuarios existentes
            $users = User::all();

            Group::factory(10)->create()->each(function ($group) use ($users) {
                $group->creator_id = $users->random()->id;
                $group->registered_by = $users->random()->id;
                $group->save();

                $memberCount = rand(5, min(15, $users->count()));
                $memberIds = $users->random($memberCount)->pluck('id')->toArray();

                if (! in_array($group->creator_id, $memberIds)) {
                    $memberIds[array_rand($memberIds)] = $group->creator_id;
                }

                $payload = [];
                foreach ($memberIds as $uid) {
                    $payload[$uid] = [
                        'role' => $uid === $group->creator_id ? 'owner' : (rand(1, 10) > 8 ? 'moderator' : 'member'),
                        'status' => 'active',
                        'registered_by' => $users->random()->id,
                        'joined_at' => now(),
                    ];
                }

                $group->members()->syncWithoutDetaching($payload);
            });
        }
    }
}
