<?php

use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

function adminUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

function supplierUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('supplier');

    return $user;
}

it('requires auth to list suppliers', function () {
    $this->get(route('suppliers.index'))->assertRedirect(route('login'));
});

it('lets admins list suppliers', function () {
    Supplier::factory()->count(3)->create();

    $this->actingAs(adminUser())
        ->get(route('suppliers.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Suppliers/Index')
            ->has('suppliers.data', 3)
        );
});

it('lets admins create a supplier', function () {
    $response = $this->actingAs(adminUser())->post(route('suppliers.store'), [
        'name' => 'Acme Power',
        'country' => 'IT',
        'status' => 'active',
    ]);

    $response->assertRedirect();
    expect(Supplier::where('name', 'Acme Power')->exists())->toBeTrue();
});

it('forbids buyers from creating suppliers', function () {
    $buyer = User::factory()->create();
    $buyer->assignRole('buyer');

    $this->actingAs($buyer)
        ->post(route('suppliers.store'), [
            'name' => 'Should Fail',
            'country' => 'IT',
            'status' => 'active',
        ])
        ->assertForbidden();
});

it('lets the owning supplier user update their own supplier', function () {
    $owner = supplierUser();
    $supplier = Supplier::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->put(route('suppliers.update', $supplier), ['name' => 'Renamed'])
        ->assertRedirect();

    expect($supplier->fresh()->name)->toBe('Renamed');
});

it('prevents a supplier user from updating another supplier', function () {
    $owner = supplierUser();
    $other = supplierUser();
    $supplier = Supplier::factory()->for($owner)->create();

    $this->actingAs($other)
        ->put(route('suppliers.update', $supplier), ['name' => 'Hacked'])
        ->assertForbidden();
});
