<?php

use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Carbon;

test('guests are redirected to the login page', function () {
    $this->get(route('quotes.today'))->assertRedirect(route('login'));
});

test('the quote changes day by day and loops back', function () {
    $first = Quote::factory()->create(['sort_order' => 1]);
    $second = Quote::factory()->create(['sort_order' => 2]);

    $day = Carbon::parse('2026-10-24', 'Asia/Tokyo');
    $today = Quote::forDate($day);

    expect(Quote::forDate($day->copy()->addDay())->id)->not->toBe($today->id)
        ->and(Quote::forDate($day->copy()->addDays(2))->id)->toBe($today->id)
        ->and([$first->id, $second->id])->toContain($today->id);
});

test('the quote page shows today\'s quote and its meaning', function () {
    Quote::factory()->create([
        'body' => '急がば回れ',
        'meaning' => '確かな道を一歩ずつ進むほうが、結局は早く着く。',
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('quotes.today'))
        ->assertOk()
        ->assertSee('急がば回れ')
        ->assertSee('確かな道を一歩ずつ進むほうが、結局は早く着く。');
});

test('the home shows today\'s quote', function () {
    Quote::factory()->create(['body' => '石の上にも三年']);

    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('石の上にも三年');
});

test('the quote page works when there are no quotes yet', function () {
    expect(Quote::today())->toBeNull();

    $this->actingAs(User::factory()->create())
        ->get(route('quotes.today'))
        ->assertOk()
        ->assertSee('準備中');
});
