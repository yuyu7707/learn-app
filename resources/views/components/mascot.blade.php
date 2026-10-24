@props([
    'message',
    'emoji' => '🐱',
])

{{-- ネコと吹き出し。イラストの画像ができたら、絵文字の部分を <img> に差し替える --}}
<div {{ $attributes->class('flex items-end gap-3') }}>
    <span class="inline-block origin-bottom animate-sway text-5xl leading-none" aria-hidden="true">{{ $emoji }}</span>
    <p class="relative animate-pop rounded-2xl bg-wood-light px-4 py-3 font-heading font-bold leading-snug text-ink shadow-sm before:absolute before:-left-2 before:bottom-4 before:size-4 before:rotate-45 before:bg-wood-light">
        <span class="relative">{{ $message }}</span>
    </p>
</div>
