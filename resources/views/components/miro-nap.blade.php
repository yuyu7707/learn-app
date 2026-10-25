@props([
    'message',
    'photo' => 'banzai',
])

{{-- 学び進めたときに出る、おひるね中のミロ（ひと休み）。photo は public/images/miro-nap-{photo}.jpg --}}
<figure {{ $attributes->class('animate-rise overflow-hidden rounded-2xl border-4 border-wood bg-wood-light') }} data-test="miro-nap">
    <img
        src="{{ asset('images/miro-nap-'.$photo.'.jpg') }}"
        alt="{{ ['sofa' => 'ソファで横になって寝ているミロ', 'knit' => 'ニットの上で丸くなって寝ているミロ'][$photo] ?? 'ばんざいして、ビローンと伸びて寝ているミロ' }}"
        width="800"
        height="600"
        loading="lazy"
        class="aspect-[4/3] w-full object-cover"
    >
    <figcaption class="px-5 py-4 text-center font-heading font-bold text-ink">
        {{ $message }}<br>
        おつかれさま。ミロも おひるね中…💤<br>
        <span class="text-sm font-normal text-ink-muted">あせらず、少しずつで大丈夫だよ。</span>
    </figcaption>
</figure>
