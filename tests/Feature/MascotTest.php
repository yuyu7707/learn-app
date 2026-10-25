<?php

use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Models\Term;
use App\Models\User;
use Livewire\Livewire;

test('言葉の一覧で、ミロが案内する', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('terms.index'))
        ->assertOk()
        ->assertSee('images/miro-hello.jpg', false)
        ->assertSee('ミロだよ。好きな言葉から いっしょに学ぼう！');
});

test('言葉の説明で、ミロが声をかける', function () {
    $term = Term::factory()->create(['name' => '複利']);

    $this->actingAs(User::factory()->create())
        ->get(route('terms.show', $term))
        ->assertOk()
        ->assertSee('data-test="mascot-miro"', false)
        ->assertSee('複利って、なんだろう？ ミロが教えるね');
});

test('言葉の説明では、考えている顔のミロが出る', function () {
    $term = Term::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('terms.show', $term))
        ->assertOk()
        ->assertSee('images/miro-think.jpg', false);
});

/**
 * 並び順に $count 個の言葉を作り、それぞれに選択肢1つのクイズをつける。
 *
 * @return list<Term>
 */
function createTermsWithQuiz(int $count): array
{
    $terms = [];

    foreach (range(1, $count) as $order) {
        $term = Term::factory()->create(['sort_order' => $order]);
        $question = QuizQuestion::factory()->for($term)->create();
        QuizChoice::factory()->for($question, 'question')->create();
        $terms[] = $term->fresh();
    }

    return $terms;
}

test('はじめの言葉のクイズでは、おひるねのミロは出ない', function () {
    $terms = createTermsWithQuiz(5);
    $choice = $terms[1]->quizQuestion->choices->first();

    $this->actingAs(User::factory()->create());

    Livewire::test('pages::terms.quiz', ['term' => $terms[1]])
        ->call('choose', $choice->id)
        ->assertDontSee('ミロも おひるね中');
});

test('3つめの言葉のクイズで答えを選ぶと、おひるねのミロがねぎらう', function () {
    $terms = createTermsWithQuiz(5);
    $choice = $terms[2]->quizQuestion->choices->first();

    $this->actingAs(User::factory()->create());

    Livewire::test('pages::terms.quiz', ['term' => $terms[2]])
        ->assertDontSee('ミロも おひるね中')
        ->call('choose', $choice->id)
        ->assertSee('3つめまで来たね。')
        ->assertSee('ミロも おひるね中')
        ->assertSee('images/miro-nap-sofa.jpg', false);
});

test('4つめの言葉のクイズでは、ニットの上で寝ているミロが出る', function () {
    $terms = createTermsWithQuiz(5);
    $choice = $terms[3]->quizQuestion->choices->first();

    $this->actingAs(User::factory()->create());

    Livewire::test('pages::terms.quiz', ['term' => $terms[3]])
        ->call('choose', $choice->id)
        ->assertSee('4つめまで来たね。')
        ->assertSee('images/miro-nap-knit.jpg', false);
});

test('最後の言葉のクイズでは、ぜんぶ学んだことをほめる', function () {
    $terms = createTermsWithQuiz(5);
    $choice = $terms[4]->quizQuestion->choices->first();

    $this->actingAs(User::factory()->create());

    Livewire::test('pages::terms.quiz', ['term' => $terms[4]])
        ->call('choose', $choice->id)
        ->assertSee('5つの言葉、ぜんぶ学んだね！')
        ->assertSee('images/miro-nap-banzai.jpg', false);
});

test('ホームには、おひるねのミロを出さない', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('ミロも おひるね中');
});
