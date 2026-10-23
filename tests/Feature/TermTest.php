<?php

use App\Models\Term;
use App\Models\User;

test('guests are redirected to the login page', function (string $routeName, bool $needsTerm) {
    $term = Term::factory()->create();

    $this->get(route($routeName, $needsTerm ? $term : []))
        ->assertRedirect(route('login'));
})->with([
    'ホーム' => ['dashboard', false],
    '目標' => ['goal.edit', false],
    '用語一覧' => ['terms.index', false],
    '用語詳細' => ['terms.show', true],
    'クイズ' => ['terms.quiz', true],
]);

test('terms are listed in sort order', function () {
    Term::factory()->create(['name' => '二番目の言葉', 'sort_order' => 2]);
    Term::factory()->create(['name' => '一番目の言葉', 'sort_order' => 1]);

    $this->actingAs(User::factory()->create())
        ->get(route('terms.index'))
        ->assertOk()
        ->assertSeeInOrder(['一番目の言葉', '二番目の言葉']);
});

test('term detail shows explanation, analogy and points', function () {
    $term = Term::factory()->create([
        'slug' => 'diversification',
        'name' => '分散投資',
        'explanation' => 'いくつかに分けて投資することです。',
        'analogy' => '卵をひとつのカゴに盛らない。',
        'points' => ['1つに集中させない', '大きく減りにくくなる'],
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('terms.show', $term))
        ->assertOk()
        ->assertSee('分散投資')
        ->assertSee('いくつかに分けて投資することです。')
        ->assertSee('卵をひとつのカゴに盛らない。')
        ->assertSee('1つに集中させない')
        ->assertSee('大きく減りにくくなる');
});

test('term detail returns 404 for an unknown slug', function () {
    $this->actingAs(User::factory()->create())
        ->get('/terms/unknown-word')
        ->assertNotFound();
});
