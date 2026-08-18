<?php

use App\Models\User;

it('redirects guest from home to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

it('allows authenticated user to view dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
});
