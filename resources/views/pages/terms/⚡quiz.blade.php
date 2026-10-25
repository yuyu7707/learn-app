<?php

use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Models\Term;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * 用語クイズ。「正解」「不正解」は出さず、選んだ選択肢の考え方のポイント（feedback）を見せる。
 * 回答は保存しない。3つめ以降の言葉で答えを選ぶと、おひるね中のミロがねぎらう。
 */
new class extends Component {
    public Term $term;

    public ?int $selectedChoiceId = null;

    public function render()
    {
        return $this->view()->title($this->term->name.'のクイズ');
    }

    #[Computed]
    public function question(): ?QuizQuestion
    {
        return $this->term->quizQuestion()->with('choices')->first();
    }

    #[Computed]
    public function selectedChoice(): ?QuizChoice
    {
        if ($this->selectedChoiceId === null) {
            return null;
        }

        return $this->question?->choices->firstWhere('id', $this->selectedChoiceId);
    }

    /**
     * 一覧の中で何番目か（例：3 / 5）。
     *
     * @return array{position: int, total: int}
     */
    #[Computed]
    public function progress(): array
    {
        $ids = Term::query()->orderBy('sort_order')->orderBy('id')->pluck('id');

        return [
            'position' => (int) $ids->search($this->term->id) + 1,
            'total' => $ids->count(),
        ];
    }

    public function choose(int $choiceId): void
    {
        // この問題の選択肢だけを受け付ける
        if ($this->question?->choices->contains('id', $choiceId)) {
            $this->selectedChoiceId = $choiceId;
        }
    }
}; ?>

<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <div>
        <flux:link :href="route('terms.show', $term)" wire:navigate>← {{ $term->name }}の説明へ</flux:link>
        <flux:heading size="xl" level="1" class="mt-3 font-heading">{{ $term->name }}のクイズ</flux:heading>
    </div>

    @if ($this->question)
        <section class="rounded-2xl bg-sky-card p-6">
            <p class="text-sm font-bold text-navy">Q.</p>
            <p class="mt-1 font-heading text-xl font-bold leading-snug text-ink">{{ $this->question->question }}</p>
        </section>

        <div class="flex flex-col gap-3" role="group" aria-label="選択肢">
            @foreach ($this->question->choices as $choice)
                @php($isSelected = $choice->id === $selectedChoiceId)
                <button
                    type="button"
                    wire:key="choice-{{ $choice->id }}"
                    wire:click="choose({{ $choice->id }})"
                    aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                    @class([
                        'flex min-h-14 w-full items-center gap-4 rounded-2xl border-2 bg-card p-4 text-start text-ink transition',
                        'border-brand' => $isSelected,
                        'border-line hover:border-brand' => ! $isSelected,
                    ])
                    data-sound="pon"
                    data-test="quiz-choice"
                >
                    <span @class([
                        'flex size-10 shrink-0 items-center justify-center rounded-full text-lg font-bold',
                        'bg-brand text-white' => $isSelected,
                        'bg-cream-alt text-ink' => ! $isSelected,
                    ])>{{ chr(64 + $loop->iteration) }}</span>
                    <span>{{ $choice->body }}</span>
                </button>
            @endforeach
        </div>

        @if ($this->selectedChoice)
            <section class="rounded-2xl bg-peach p-6" aria-live="polite" data-test="quiz-feedback">
                <p class="flex items-center gap-2 font-bold text-brand-dark">
                    <img src="{{ asset('images/miro-look.jpg') }}" alt="" width="40" height="40" class="size-10 rounded-full border-2 border-wood object-cover">
                    ミロのひとこと
                </p>
                <p class="mt-2 leading-relaxed text-ink">{{ $this->selectedChoice->feedback }}</p>
                <p class="mt-3 text-sm text-ink-muted">ほかの選択肢も選んで、考え方を見てみましょう。</p>
            </section>

            {{-- 学び進めたごほうび（3つめ以降の言葉） --}}
            @if ($this->progress['position'] >= 3)
                <x-miro-nap :message="$this->progress['position'] === $this->progress['total']
                    ? $this->progress['total'].'つの言葉、ぜんぶ学んだね！'
                    : $this->progress['position'].'つめまで来たね。'" />
            @endif
        @else
            <flux:text>選択肢を1つ選んでみてください。</flux:text>
        @endif
    @else
        <flux:text>この言葉のクイズは、まだ準備中です。</flux:text>
    @endif

    <flux:button :href="route('terms.index')" class="w-full" wire:navigate>
        言葉の一覧へ戻る
    </flux:button>
</div>
