<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard asks users without a goal to write one', function () {
    $this->actingAs(User::factory()->create(['goal' => null]))
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('目標を書く');
});

test('dashboard shows the goal at the top', function () {
    $this->actingAs(User::factory()->create(['goal' => '月1万円から始められるようになる']))
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['わたしの目標', '月1万円から始められるようになる', '投資の言葉を学ぶ']);
});

test('logged in users visiting the top page are sent to the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertRedirect(route('dashboard'));
});
