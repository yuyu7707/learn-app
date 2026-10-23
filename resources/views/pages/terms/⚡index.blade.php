<?php

use App\Models\Term;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('言葉の一覧')] class extends Component {
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
    <div>
        <flux:heading size="xl" level="1" class="font-heading">📖 言葉の一覧</flux:heading>
        <flux:text class="mt-2">好きな言葉から学んでください。</flux:text>
    </div>

    <ol class="flex flex-col gap-4">
        @forelse ($this->terms as $term)
            <li wire:key="term-{{ $term->id }}">
                <a
                    href="{{ route('terms.show', $term) }}"
                    wire:navigate
                    class="flex items-center gap-4 rounded-2xl border border-line bg-card p-5 transition hover:border-brand focus-visible:outline-2 focus-visible:outline-brand"
                    data-test="term-link"
                >
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-brand text-xl font-bold text-white">
                        {{ $loop->iteration }}
                    </span>
                    <span class="flex flex-col">
                        <span class="font-heading text-xl font-bold text-ink">{{ $term->name }}</span>
                        <span class="text-ink-muted">{{ $term->summary }}</span>
                    </span>
                </a>
            </li>
        @empty
            <li class="rounded-2xl border border-line bg-card p-5 text-ink-muted">まだ言葉が登録されていません。</li>
        @endforelse
    </ol>
</div>
