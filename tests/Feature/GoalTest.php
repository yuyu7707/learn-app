<?php

use App\Models\User;
use Livewire\Livewire;

test('goal page is displayed', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('goal.edit'))
        ->assertOk();
});

test('saved goal is shown on the home page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('pages::goal.edit')
        ->set('goal', '老後のために、少しずつお金を育てたい')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect($user->refresh()->goal)->toBe('老後のために、少しずつお金を育てたい');

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('老後のために、少しずつお金を育てたい');
});

test('goal longer than 100 characters is rejected', function () {
    $user = User::factory()->create(['goal' => '元の目標']);
    $this->actingAs($user);

    Livewire::test('pages::goal.edit')
        ->set('goal', str_repeat('あ', 101))
        ->call('save')
        ->assertHasErrors(['goal' => 'max']);

    expect($user->refresh()->goal)->toBe('元の目標');
});

test('saving an empty goal removes it', function () {
    $user = User::factory()->create(['goal' => '元の目標']);
    $this->actingAs($user);

    Livewire::test('pages::goal.edit')
        ->assertSet('goal', '元の目標')
        ->set('goal', '   ')
        ->call('save')
        ->assertHasNoErrors();

    expect($user->refresh()->goal)->toBeNull();
});
