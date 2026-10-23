<?php

use App\Models\Term;
use Livewire\Component;

new class extends Component {
    public Term $term;

    public function render()
    {
        return $this->view()->title($this->term->name);
    }
}; ?>

<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <div>
        <flux:link :href="route('terms.index')" wire:navigate>← 言葉の一覧へ</flux:link>
        <flux:heading size="xl" level="1" class="mt-3 font-heading">{{ $term->name }}</flux:heading>
        <flux:text class="mt-1 text-lg">{{ $term->summary }}</flux:text>
    </div>

    <section class="rounded-2xl border border-line bg-card p-6">
        <h2 class="text-sm font-bold text-brand-dark">やさしい説明</h2>
        <p class="mt-2 leading-relaxed text-ink">{{ $term->explanation }}</p>
    </section>

    <section class="rounded-2xl bg-peach p-6">
        <h2 class="text-sm font-bold text-brand-dark">💡 身近なたとえ</h2>
        <p class="mt-2 leading-relaxed text-ink">{{ $term->analogy }}</p>
    </section>

    @if (filled($term->points))
        <section class="rounded-2xl bg-sky-card p-6">
            <h2 class="text-sm font-bold text-navy">ポイント</h2>
            <ul class="mt-2 flex flex-col gap-2">
                @foreach ($term->points as $point)
                    <li class="flex gap-2 text-ink">
                        <span aria-hidden="true">✔</span>
                        <span>{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <flux:button :href="route('terms.quiz', $term)" variant="primary" class="w-full" wire:navigate>
        クイズで確かめる
    </flux:button>
</div>
