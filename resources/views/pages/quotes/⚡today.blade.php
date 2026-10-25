<?php

use App\Models\Quote;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

/*
 * 今日の格言。日付ごとに1つずつ入れ替わる（日本時間）。
 */
new #[Title('今日の格言')] class extends Component {
    #[Computed]
    public function quote(): ?Quote
    {
        return Quote::today();
    }
}; ?>

<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <flux:heading size="xl" level="1" class="text-center font-heading text-3xl">今日の格言</flux:heading>

    @if ($this->quote)
        {{-- 空と草原の中の木の看板 --}}
        <div class="relative rounded-3xl bg-linear-to-b from-sky-card via-sky-card to-mint px-5 pt-6 pb-4">
            <div class="animate-rise rounded-2xl border-[10px] border-wood bg-wood-light px-5 py-8 shadow-md">
                <p class="text-center font-heading text-2xl font-bold leading-loose text-ink" data-test="quote-body">
                    {!! nl2br(e($this->quote->body)) !!}
                </p>
            </div>
            <div class="mt-3 flex items-end justify-between px-2">
                <span class="inline-block origin-bottom animate-sway text-4xl" aria-hidden="true">🌼</span>
                <span class="inline-block animate-grow text-5xl" aria-hidden="true">🌱</span>
                <img data-sound="nya" src="{{ asset('images/miro-sign.jpg') }}" alt="ミロ" width="64" height="64" class="size-16 origin-bottom animate-sway cursor-pointer rounded-full border-4 border-wood object-cover">
            </div>
        </div>

        <section class="animate-rise rounded-3xl bg-mint p-6" style="animation-delay: 300ms">
            <h2 class="font-heading text-xl font-bold text-mint-dark">🌱 この格言の意味</h2>
            <p class="mt-2 leading-loose text-ink" data-test="quote-meaning">{{ $this->quote->meaning }}</p>
        </section>

        <flux:text class="text-center">明日は、また別の言葉が届きます。</flux:text>
    @else
        <x-mascot face="peek" message="格言は、ただいま準備中です。" class="justify-center" />
    @endif

    <a href="{{ route('terms.index') }}" wire:navigate class="btn-next">
        つぎへ（言葉を学ぶ）
        <flux:icon.chevron-right variant="solid" />
    </a>
</div>
