<?php

use App\Models\Device;
use App\Models\User;

test('authenticated user can view monitoring page', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/monitoring');

    $response->assertStatus(200);
    $response->assertSee('FIDS Monitoring');
});

test('admin can access user management', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/users');

    $response->assertStatus(200);
    $response->assertSee('User Management');
});

test('regular user cannot access user management', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/users');

    $response->assertStatus(403);
});

test('monitoring data endpoint returns json status', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/monitoring/data');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'stats' => ['total', 'online', 'offline', 'warning', 'maintenance'],
        'devices',
        'timestamp',
    ]);
});
