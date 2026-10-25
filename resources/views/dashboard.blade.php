<x-layouts::app :title="__('ホーム')">
    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
        {{-- わたしの目標（いつも上に出す） --}}
        <section class="rounded-2xl bg-sky-card p-6" data-test="goal-card">
            <p class="text-sm font-bold text-navy">🌱 わたしの目標</p>

            @if (filled(auth()->user()->goal))
                <p class="mt-2 font-heading text-2xl font-bold leading-snug text-ink" data-test="goal-text">
                    {{ auth()->user()->goal }}
                </p>
                <flux:link :href="route('goal.edit')" class="mt-3 inline-block" wire:navigate>目標を変える</flux:link>
            @else
                <p class="mt-2 text-ink">
                    何のために学ぶのかを書いておくと、続けやすくなります。
                </p>
                <flux:button :href="route('goal.edit')" variant="primary" class="mt-4" wire:navigate>
                    目標を書く
                </flux:button>
            @endif
        </section>

        {{-- 今日の格言 --}}
        @php($quote = \App\Models\Quote::today())
        @if ($quote)
            <a href="{{ route('quotes.today') }}" wire:navigate class="block rounded-2xl border-4 border-wood bg-wood-light p-6 transition hover:brightness-105" data-test="quote-card">
                <p class="text-sm font-bold text-brand-dark">✨ 今日の格言</p>
                <p class="mt-2 font-heading text-xl font-bold leading-relaxed text-ink">{!! nl2br(e($quote->body)) !!}</p>
                <p class="mt-3 font-bold text-pink">意味を読む →</p>
            </a>
        @endif

        {{-- 用語を学ぶ --}}
        <section class="rounded-2xl border border-line bg-card p-6">
            <h2 class="font-heading text-2xl font-bold text-ink">📖 投資の言葉を学ぶ</h2>
            <p class="mt-2 text-ink-muted">
                1つの言葉を、やさしい説明と身近なたとえで学び、クイズで確かめます。気になる言葉から始めてください。
            </p>
            <flux:button :href="route('terms.index')" variant="primary" class="mt-4 w-full" wire:navigate>
                言葉の一覧へ
            </flux:button>
        </section>
    </div>
</x-layouts::app>
