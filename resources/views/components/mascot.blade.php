@props([
    'message',
])

{{-- 案内役のミロ（うちの猫）と吹き出し --}}
<div {{ $attributes->class('flex items-end gap-3') }}>
    <figure class="flex shrink-0 origin-bottom animate-sway flex-col items-center gap-1">
        <img
            src="{{ asset('images/miro-face.jpg') }}"
            alt="ミロ"
            width="80"
            height="80"
            class="size-20 rounded-full border-4 border-wood object-cover shadow-sm"
            data-test="mascot-miro"
        >
        <figcaption class="text-xs font-bold text-ink-muted">ミロ</figcaption>
    </figure>
    <p class="relative mb-6 animate-pop rounded-2xl bg-wood-light px-4 py-3 font-heading font-bold leading-snug text-ink shadow-sm before:absolute before:-left-2 before:bottom-4 before:size-4 before:rotate-45 before:bg-wood-light">
        <span class="relative">{{ $message }}</span>
    </p>
</div>
