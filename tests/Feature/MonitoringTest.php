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

test('authenticated user can toggle maintenance mode on device', function () {
    $user = User::factory()->create();
    $device = Device::create([
        'device_name' => 'TEST_DEV_01',
        'location' => 'GATE_1',
        'ip_address' => '127.0.0.1',
        'status' => 'online',
        'subnet' => '255.255.255.0',
        'gateway' => '192.168.1.1',
    ]);

    // Enter maintenance
    $response = $this->actingAs($user)->post("/devices/{$device->id}/toggle-maintenance");
    $response->assertRedirect();
    
    $device->refresh();
    expect($device->status)->toBe('maintenance');
    $this->assertDatabaseHas('device_notifications', [
        'device_id' => $device->id,
        'type' => 'maintenance',
    ]);
});
