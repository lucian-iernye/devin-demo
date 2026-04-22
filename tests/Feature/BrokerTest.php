<?php

use App\Models\Broker;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

function adminBrokerUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

function brokerUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('broker');

    return $user;
}

it('requires auth to list brokers', function () {
    $this->get(route('brokers.index'))->assertRedirect(route('login'));
});

it('lets admins list brokers', function () {
    Broker::factory()->count(3)->create();

    $this->actingAs(adminBrokerUser())
        ->get(route('brokers.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Brokers/Index')
            ->has('brokers.data', 3)
        );
});

it('lets admins create a broker', function () {
    $response = $this->actingAs(adminBrokerUser())->post(route('brokers.store'), [
        'name' => 'Acme Brokers',
        'country' => 'IT',
        'default_commission_rate' => 0.025,
        'status' => 'active',
    ]);

    $response->assertRedirect();
    expect(Broker::where('name', 'Acme Brokers')->exists())->toBeTrue();
});

it('forbids buyers from creating brokers', function () {
    $buyer = User::factory()->create();
    $buyer->assignRole('buyer');

    $this->actingAs($buyer)
        ->post(route('brokers.store'), [
            'name' => 'Should Fail',
            'country' => 'IT',
            'default_commission_rate' => 0.02,
            'status' => 'active',
        ])
        ->assertForbidden();
});

it('lets the owning broker user update their own broker', function () {
    $owner = brokerUser();
    $broker = Broker::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->put(route('brokers.update', $broker), ['name' => 'Renamed'])
        ->assertRedirect();

    expect($broker->fresh()->name)->toBe('Renamed');
});

it('prevents a broker user from updating another broker', function () {
    $owner = brokerUser();
    $other = brokerUser();
    $broker = Broker::factory()->for($owner)->create();

    $this->actingAs($other)
        ->put(route('brokers.update', $broker), ['name' => 'Hacked'])
        ->assertForbidden();
});
