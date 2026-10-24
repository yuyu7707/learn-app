<?php

use App\Models\Term;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('言葉を選ぼう')] class extends Component {
    /**
     * @return Collection<int, Term>
     */
    #[Computed]
    public function terms(): Collection
    {
        return Term::query()->orderBy('sort_order')->orderBy('id')->get();
    }
}; ?>

<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <flux:heading size="xl" level="1" class="text-center font-heading text-3xl">言葉を選ぼう</flux:heading>

    <ol class="flex flex-col gap-4">
        @forelse ($this->terms as $term)
            @php($numberColor = ['bg-num-1', 'bg-num-2', 'bg-num-3', 'bg-num-4', 'bg-num-5'][$loop->index % 5])
            <li wire:key="term-{{ $term->id }}">
                <a
                    href="{{ route('terms.show', $term) }}"
                    wire:navigate
                    class="flex items-center gap-4 rounded-3xl border-2 border-line bg-card p-4 shadow-sm transition hover:border-pink hover:bg-pink-soft focus-visible:outline-2 focus-visible:outline-pink"
                    data-test="term-link"
                >
                    <span class="{{ $numberColor }} flex size-14 shrink-0 items-center justify-center rounded-full font-heading text-2xl font-bold text-white">
                        {{ $loop->iteration }}
                    </span>
                    <span class="flex min-w-0 flex-1 flex-col">
                        <span class="font-heading text-xl font-bold text-ink">{{ $term->name }}</span>
                        <span class="text-ink-muted">{{ $term->summary }}</span>
                    </span>
                    <span class="shrink-0 text-4xl" aria-hidden="true">{{ $term->icon ?? '🌱' }}</span>
                </a>
            </li>
        @empty
            <li class="rounded-3xl border border-line bg-card p-5 text-ink-muted">まだ言葉が登録されていません。</li>
        @endforelse
    </ol>

    <x-mascot message="好きなところから はじめていいよ！" class="justify-center" />
</div>
