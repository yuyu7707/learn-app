<?php

use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Models\Term;
use App\Models\User;
use Livewire\Livewire;

function createQuizTerm(): Term
{
    $term = Term::factory()->create(['name' => '複利']);
    $question = QuizQuestion::factory()->for($term)->create(['question' => '増えた分にも利益がつくしくみは？']);

    QuizChoice::factory()->for($question, 'question')->create(['body' => '単利', 'feedback' => '単利は最初のお金にだけ利益がつきます。', 'sort_order' => 1]);
    QuizChoice::factory()->for($question, 'question')->create(['body' => '複利', 'feedback' => '複利は雪だるまのように育ちます。', 'sort_order' => 2]);

    return $term;
}

test('quiz page shows the question and choices', function () {
    $term = createQuizTerm();

    $this->actingAs(User::factory()->create())
        ->get(route('terms.quiz', $term))
        ->assertOk()
        ->assertSee('増えた分にも利益がつくしくみは？')
        ->assertSeeInOrder(['単利', '複利']);
});

test('choosing a choice shows its feedback without saying correct or wrong', function () {
    $term = createQuizTerm();
    $choice = $term->quizQuestion->choices->firstWhere('body', '単利');

    $this->actingAs(User::factory()->create());

    Livewire::test('pages::terms.quiz', ['term' => $term])
        ->assertDontSee('単利は最初のお金にだけ利益がつきます。')
        ->call('choose', $choice->id)
        ->assertSet('selectedChoiceId', $choice->id)
        ->assertSee('単利は最初のお金にだけ利益がつきます。')
        ->assertDontSee('複利は雪だるまのように育ちます。')
        ->assertDontSee('正解')
        ->assertDontSee('不正解');
});

test('a choice from another question is ignored', function () {
    $term = createQuizTerm();
    $otherChoice = QuizChoice::factory()->create();

    $this->actingAs(User::factory()->create());

    Livewire::test('pages::terms.quiz', ['term' => $term])
        ->call('choose', $otherChoice->id)
        ->assertSet('selectedChoiceId', null);
});
