<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => null])
    </head>
    <body class="min-h-screen bg-cream text-ink antialiased">
        <main class="mx-auto flex min-h-screen w-full max-w-2xl flex-col justify-center gap-8 px-6 py-12">
            <div class="flex flex-col gap-4">
                <p class="font-bold text-brand-dark">中高年に贈る</p>
                <h1 class="font-heading text-4xl font-bold leading-tight sm:text-5xl">
                    幼稚園児でも<br>わかる投資
                </h1>
                <p class="text-xl leading-relaxed text-ink-muted">
                    むずかしい投資を、やさしい言葉で、少しずつ。
                </p>
            </div>

            <ul class="flex flex-col gap-3">
                <li class="rounded-2xl bg-sky-card p-4">📖 投資の言葉を、身近なたとえで学べます</li>
                <li class="rounded-2xl bg-sky-card p-4">💡 クイズで、考え方を確かめられます</li>
                <li class="rounded-2xl bg-sky-card p-4">🌱 自分の目標を、いつも目にしながら続けられます</li>
            </ul>

            <div class="flex flex-col gap-3 sm:flex-row">
                @if (Route::has('register'))
                    <flux:button :href="route('register')" variant="primary" class="w-full sm:w-auto">
                        はじめる（会員登録）
                    </flux:button>
                @endif
                <flux:button :href="route('login')" class="w-full sm:w-auto">
                    ログイン
                </flux:button>
            </div>
        </main>

        @fluxScripts
    </body>
</html>
