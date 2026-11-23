<?php

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createGroupFor(User $owner): Group
{
    $group = Group::factory()->create([
        'creator_id' => $owner->id,
        'registered_by' => $owner->id,
    ]);

    $group->members()->attach($owner->id, [
        'role' => 'owner',
        'status' => 'active',
        'registered_by' => $owner->id,
        'joined_at' => now(),
    ]);

    return $group;
}

it('registers the creator as owner in the pivot table', function () {
    $owner = User::factory()->create();

    $this->actingAs($owner)
        ->post(route('groups.store'), [
            'name' => 'Test Group',
            'description' => 'Test description',
            'privacy' => 'public',
        ])
        ->assertRedirect();

    $group = Group::firstOrFail();

    expect($group->members()->whereKey($owner->id)->exists())->toBeTrue();
    expect($group->members()->whereKey($owner->id)->first()->pivot->role)->toBe('owner')
        ->and($group->members()->whereKey($owner->id)->first()->pivot->status)->toBe('active');
});

it('allows the owner to add, update and remove members', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $group = createGroupFor($owner);

    $this->actingAs($owner)
        ->post(route('groups.members.store', $group), [
            'user_id' => $member->id,
            'role' => 'moderator',
            'status' => 'active',
        ])
        ->assertRedirect();

    $pivot = $group->members()->whereKey($member->id)->first()->pivot;
    expect($pivot->role)->toBe('moderator')
        ->and($pivot->status)->toBe('active')
        ->and($pivot->registered_by)->toBe($owner->id);

    $this->actingAs($owner)
        ->put(route('groups.members.update', [$group, $member]), [
            'role' => 'admin',
            'status' => 'inactive',
        ])
        ->assertRedirect();

    $pivot = $group->members()->whereKey($member->id)->first()->pivot;
    expect($pivot->role)->toBe('admin')
        ->and($pivot->status)->toBe('inactive');

    $this->actingAs($owner)
        ->delete(route('groups.members.destroy', [$group, $member]))
        ->assertRedirect();

    expect($group->members()->whereKey($member->id)->exists())->toBeFalse();
});

it('prevents a non-owner from managing members', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $member = User::factory()->create();
    $group = createGroupFor($owner);

    $this->actingAs($intruder)
        ->post(route('groups.members.store', $group), [
            'user_id' => $member->id,
            'role' => 'member',
            'status' => 'active',
        ])
        ->assertRedirect();

    expect($group->members()->whereKey($member->id)->exists())->toBeFalse();
});

it('shows the memberships listing from the panel', function () {
    $owner = User::factory()->create();
    $group = createGroupFor($owner);

    $this->actingAs($owner)
        ->get(route('memberships.index'))
        ->assertOk()
        ->assertSee('Membresías')
        ->assertSee($group->name);
});

it('shows the members table with role and status', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create(['name' => 'Visible Member']);
    $group = createGroupFor($owner);

    $group->members()->attach($member->id, [
        'role' => 'member',
        'status' => 'active',
        'registered_by' => $owner->id,
        'joined_at' => now(),
    ]);

    $this->actingAs($owner)
        ->get(route('groups.show', $group))
        ->assertOk()
        ->assertSee('Visible Member')
        ->assertSee('Miembros')
        ->assertSee('Agregar miembro');
});
