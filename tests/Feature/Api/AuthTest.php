<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows an authenticated user to log out via the API', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response->assertNoContent();
    $this->assertGuest();
});
