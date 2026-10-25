@props([
    'message',
])

{{-- 学び進めたときに出る、おひるね中のミロ（ひと休み） --}}
<figure {{ $attributes->class('animate-rise overflow-hidden rounded-2xl border-4 border-wood bg-wood-light') }} data-test="miro-nap">
    <img
        src="{{ asset('images/miro-nap.jpg') }}"
        alt="ばんざいして、ビローンと伸びて寝ているミロ"
        width="600"
        height="800"
        loading="lazy"
        class="aspect-[4/3] w-full object-cover object-top"
    >
    <figcaption class="px-5 py-4 text-center font-heading font-bold text-ink">
        {{ $message }}<br>
        おつかれさま。ミロも おひるね中…💤<br>
        <span class="text-sm font-normal text-ink-muted">あせらず、少しずつで大丈夫だよ。</span>
    </figcaption>
</figure>
