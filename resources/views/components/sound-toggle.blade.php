{{-- 効果音のオン・オフ。表示と切り替えは resources/js/app.js が受け持つ --}}
<button
    type="button"
    data-sound-toggle
    aria-pressed="true"
    {{ $attributes->class('flex min-h-12 items-center justify-center rounded-full border-2 border-line bg-card px-4 font-bold text-ink transition hover:border-pink focus-visible:outline-2 focus-visible:outline-pink') }}
    data-test="sound-toggle"
>
    <span data-sound-label>🔔 音：オン</span>
</button>
