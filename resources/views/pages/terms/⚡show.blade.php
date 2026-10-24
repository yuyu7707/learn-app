<?php

use App\Models\Term;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Term $term;

    public function render()
    {
        return $this->view()->title($this->term->name);
    }

    /**
     * 一覧の中で何番目か（例：1 / 5）。
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
}; ?>

<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    {{-- 上の帯：戻る・何番目か --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('terms.index') }}" wire:navigate class="flex size-12 items-center justify-center rounded-full text-2xl text-ink hover:bg-cream-alt" aria-label="言葉の一覧へ戻る">
            <flux:icon.chevron-left variant="solid" />
        </a>
        <p class="font-heading font-bold text-ink">{{ $this->progress['position'] }}つめの言葉</p>
        <p class="min-w-12 text-end font-bold text-ink-muted" data-test="term-progress">
            {{ $this->progress['position'] }} / {{ $this->progress['total'] }}
        </p>
    </div>

    {{-- 見出しの帯 --}}
    <div class="animate-rise rounded-3xl bg-pink-soft px-6 py-5 text-center">
        <flux:heading size="xl" level="1" class="font-heading text-3xl !text-pink-dark">{{ $term->name }}</flux:heading>
        <p class="mt-2 font-heading font-bold text-pink-dark">〜{{ $term->summary }}〜</p>
    </div>

    {{-- 絵の場所（イラストができたら差し替える） --}}
    <div class="flex flex-col items-center gap-4 rounded-3xl bg-linear-to-b from-sky-card to-mint px-6 pt-6 pb-5">
        <span class="inline-block animate-grow text-8xl leading-none" aria-hidden="true">{{ $term->icon ?? '🌱' }}</span>
        <x-mascot :message="$term->name.'って、なんだろう？'" />
    </div>

    <p class="text-lg leading-loose text-ink">{{ $term->explanation }}</p>

    <section class="rounded-3xl bg-peach p-6">
        <h2 class="font-heading font-bold text-brand-dark">🏠 身近なたとえ</h2>
        <p class="mt-2 leading-relaxed text-ink">{{ $term->analogy }}</p>
    </section>

    @if (filled($term->points))
        <section class="rounded-3xl bg-mint p-6">
            <h2 class="font-heading text-xl font-bold text-mint-dark">💡 ポイント</h2>
            <ul class="mt-3 flex flex-col gap-2">
                @foreach ($term->points as $point)
                    <li class="flex gap-2 text-ink">
                        <span class="text-mint-dark" aria-hidden="true">●</span>
                        <span>{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <a href="{{ route('terms.quiz', $term) }}" wire:navigate class="btn-next">
        つぎへ（クイズ）
        <flux:icon.chevron-right variant="solid" />
    </a>
</div>
