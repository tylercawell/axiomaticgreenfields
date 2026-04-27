<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

it('redirects guests away form commission notes', function () {
    $response = $this->get(route('commissionNotes'));
    $response->assertRedirect(route('login'));
});

it('forbids authenticated users without viewing permission', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('commissionNotes'));
    $response->assertForbidden();
});

it('allows authenticated users with viewing permission to open commission notes index', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('view commission notes');
    $this->actingAs($user);

    $response = $this->get(route('commissionNotes'));
    $response->assertOk();
});