<?php

use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Models\Term;
use App\Models\User;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

/**
 * 追加・編集フォームに入れる正しい値。
 *
 * @return array<string, mixed>
 */
function validTermInput(): array
{
    return [
        'name' => '長期投資',
        'slug' => 'long-term',
        'summary' => '長い時間をかけて育てること',
        'explanation' => '短い値動きに一喜一憂せず、長い時間をかけて育てる考え方です。',
        'analogy' => '木を育てるようなもの。',
        'points' => ['あわてない', '', ''],
        'sort_order' => 6,
        'question' => '「木を育てるように」は、どの考え方のたとえでしょう？',
        'choices' => [
            ['body' => '長期投資', 'feedback' => '時間を味方にする考え方です。'],
            ['body' => '短期売買', 'feedback' => '短い期間で売り買いすることです。'],
            ['body' => '分散投資', 'feedback' => 'お金を分けることです。'],
        ],
    ];
}

function fillTermForm(Testable $component, array $input): Testable
{
    foreach ($input as $key => $value) {
        $component->set($key, $value);
    }

    return $component;
}

test('guests are redirected to the login page', function () {
    $this->get(route('admin.terms.index'))->assertRedirect(route('login'));
});

test('regular users cannot open the admin pages', function () {
    $term = Term::factory()->create();

    $this->actingAs(User::factory()->create());

    $this->get(route('admin.terms.index'))->assertForbidden();
    $this->get(route('admin.terms.create'))->assertForbidden();
    $this->get(route('admin.terms.edit', $term))->assertForbidden();
});

test('regular users do not see the admin menu', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertDontSee('言葉の管理');
});

test('admins can see the term list', function () {
    Term::factory()->create(['name' => '複利']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.terms.index'))
        ->assertOk()
        ->assertSee('複利');
});

test('admins can add a term with its quiz', function () {
    $this->actingAs(User::factory()->admin()->create());

    fillTermForm(Livewire::test('pages::admin.terms.form'), validTermInput())
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.terms.index'));

    $term = Term::where('slug', 'long-term')->firstOrFail();

    expect($term->name)->toBe('長期投資')
        ->and($term->points)->toBe(['あわてない'])
        ->and($term->quizQuestion->question)->toBe('「木を育てるように」は、どの考え方のたとえでしょう？')
        ->and($term->quizQuestion->choices->pluck('body')->all())->toBe(['長期投資', '短期売買', '分散投資']);
});

test('admins can edit a term and its choices', function () {
    $term = Term::factory()->create(['slug' => 'risk', 'name' => 'リスク']);
    $question = QuizQuestion::factory()->for($term)->create();
    QuizChoice::factory()->count(3)->for($question, 'question')->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test('pages::admin.terms.form', ['term' => $term])
        ->assertSet('name', 'リスク')
        ->set('name', 'リスク（ふれはば）')
        ->set('choices.0.body', '新しい選択肢')
        ->call('save')
        ->assertHasNoErrors();

    $term->refresh();

    expect($term->name)->toBe('リスク（ふれはば）')
        ->and($term->quizQuestion->choices)->toHaveCount(3)
        ->and($term->quizQuestion->choices->first()->body)->toBe('新しい選択肢');
});

test('slug must be unique and url friendly', function () {
    Term::factory()->create(['slug' => 'long-term']);

    $this->actingAs(User::factory()->admin()->create());

    fillTermForm(Livewire::test('pages::admin.terms.form'), validTermInput())
        ->call('save')
        ->assertHasErrors(['slug' => 'unique']);

    fillTermForm(Livewire::test('pages::admin.terms.form'), [...validTermInput(), 'slug' => '長期 投資'])
        ->call('save')
        ->assertHasErrors(['slug' => 'regex']);
});

test('every choice needs a feedback', function () {
    $this->actingAs(User::factory()->admin()->create());

    $input = validTermInput();
    $input['choices'][1]['feedback'] = '';

    fillTermForm(Livewire::test('pages::admin.terms.form'), $input)
        ->call('save')
        ->assertHasErrors(['choices.1.feedback' => 'required']);

    expect(Term::count())->toBe(0);
});

test('admins can delete a term together with its quiz', function () {
    $term = Term::factory()->create();
    $question = QuizQuestion::factory()->for($term)->create();
    QuizChoice::factory()->count(3)->for($question, 'question')->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test('pages::admin.terms.index')
        ->call('delete', $term->id);

    expect(Term::count())->toBe(0)
        ->and(QuizQuestion::count())->toBe(0)
        ->and(QuizChoice::count())->toBe(0);
});
