<?php

use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Models\Term;
use App\Models\User;

test('画面に音のオン・オフボタンが出る', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('data-sound-toggle', false)
        ->assertSee('音：オン');
});

test('クイズの選択肢に効果音の目印がつく', function () {
    $term = Term::factory()->create();
    $question = QuizQuestion::factory()->for($term)->create();
    QuizChoice::factory()->for($question, 'question')->create();

    $this->actingAs(User::factory()->create())
        ->get(route('terms.quiz', $term))
        ->assertOk()
        ->assertSee('data-sound="pon"', false);
});
