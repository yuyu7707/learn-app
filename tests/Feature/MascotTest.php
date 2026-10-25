<?php

use App\Models\Term;
use App\Models\User;

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

test('ホームの下で、ミロがおひるねしている', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('images/miro-nap.jpg', false)
        ->assertSee('ミロも おひるね中');
});
