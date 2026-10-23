<?php

use App\Models\Term;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('言葉の管理')] class extends Component {
    /**
     * @return Collection<int, Term>
     */
    #[Computed]
    public function terms(): Collection
    {
        return Term::query()->withCount('quizQuestion')->orderBy('sort_order')->orderBy('id')->get();
    }

    /**
     * 言葉を削除する。クイズと選択肢も一緒に消える。
     */
    public function delete(int $termId): void
    {
        $this->authorize('admin');

        $term = Term::findOrFail($termId);
        $name = $term->name;
        $term->delete();

        unset($this->terms);

        Flux::toast(variant: 'success', text: "「{$name}」を削除しました。");
    }
}; ?>

<div class="mx-auto flex w-full max-w-3xl flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <flux:heading size="xl" level="1" class="font-heading">🛠 言葉の管理</flux:heading>
            <flux:text class="mt-2">言葉とクイズを追加・編集・削除できます。利用者の画面にすぐ反映されます。</flux:text>
        </div>
        <flux:button :href="route('admin.terms.create')" variant="primary" icon="plus" wire:navigate>
            言葉を追加する
        </flux:button>
    </div>

    <ul class="flex flex-col gap-3">
        @forelse ($this->terms as $term)
            <li wire:key="admin-term-{{ $term->id }}" class="flex flex-col gap-3 rounded-2xl border border-line bg-card p-5 sm:flex-row sm:items-center" data-test="admin-term-row">
                <div class="flex flex-1 flex-col">
                    <span class="text-sm text-ink-muted">並び順 {{ $term->sort_order }} ／ {{ $term->slug }}</span>
                    <span class="font-heading text-xl font-bold text-ink">{{ $term->name }}</span>
                    <span class="text-ink-muted">{{ $term->summary }}</span>
                    @if ($term->quiz_question_count === 0)
                        <span class="mt-1 text-sm font-bold text-brand-dark">クイズがまだありません</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <flux:button :href="route('terms.show', $term)" size="sm" wire:navigate>見る</flux:button>
                    <flux:button :href="route('admin.terms.edit', $term)" size="sm" variant="primary" wire:navigate>編集</flux:button>
                    <flux:button
                        size="sm"
                        variant="danger"
                        wire:click="delete({{ $term->id }})"
                        wire:confirm="「{{ $term->name }}」を削除しますか？クイズも一緒に消えます。"
                        data-test="delete-term-button"
                    >削除</flux:button>
                </div>
            </li>
        @empty
            <li class="rounded-2xl border border-line bg-card p-5 text-ink-muted">まだ言葉がありません。</li>
        @endforelse
    </ul>
</div>
