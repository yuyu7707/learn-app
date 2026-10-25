@props([
    'message',
    'face' => 'hello',
])

{{-- 案内役のミロ（うちの猫）と吹き出し。face は public/images/miro-{face}.jpg。写真を差し替えたら ?v= の数字を上げる（端末に残った古い写真を読み直させるため） --}}
<div {{ $attributes->class('flex items-end gap-3') }}>
    <figure class="flex shrink-0 origin-bottom animate-sway flex-col items-center gap-1">
        <button type="button" data-sound="nya" aria-label="ミロをなでる（ニャーと鳴きます）" class="rounded-full focus-visible:outline-2 focus-visible:outline-pink">
        <img
            src="{{ asset('images/miro-'.$face.'.jpg').'?v=2' }}"
            alt="ミロ"
            width="80"
            height="80"
            class="size-20 rounded-full border-4 border-wood object-cover shadow-sm"
            data-test="mascot-miro"
        >
        </button>
        <figcaption class="text-xs font-bold text-ink-muted">ミロ</figcaption>
    </figure>
    <p class="relative mb-6 animate-pop rounded-2xl bg-wood-light px-4 py-3 font-heading font-bold leading-snug text-ink shadow-sm before:absolute before:-left-2 before:bottom-4 before:size-4 before:rotate-45 before:bg-wood-light">
        <span class="relative"><span class="text-pink">ニャー！</span> {{ $message }}</span>
    </p>
</div>
