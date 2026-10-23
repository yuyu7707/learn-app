<?php

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('わたしの目標')] class extends Component {
    public string $goal = '';

    public function mount(): void
    {
        $this->goal = Auth::user()->goal ?? '';
    }

    /**
     * 目標を保存する。空なら目標を消す。
     */
    public function save(): void
    {
        $validated = $this->validate([
            'goal' => ['nullable', 'string', 'max:100'],
        ]);

        $goal = trim((string) ($validated['goal'] ?? ''));

        $user = Auth::user();
        $user->goal = $goal === '' ? null : $goal;
        $user->save();

        Flux::toast(variant: 'success', text: $goal === '' ? '目標を消しました。' : '目標を保存しました。');

        $this->redirectRoute('dashboard', navigate: true);
    }
}; ?>

<div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1" class="font-heading">🌱 わたしの目標</flux:heading>
        <flux:text class="mt-2">ホームのいちばん上に、いつも表示されます。</flux:text>
    </div>

    <form wire:submit="save" class="flex flex-col gap-6">
        <flux:input
            wire:model="goal"
            label="目標（100文字まで）"
            placeholder="例：老後のために、少しずつお金を育てたい"
            maxlength="100"
            autofocus
        />

        <flux:text>空にして保存すると、目標を消せます。</flux:text>

        <div class="flex flex-col gap-3 sm:flex-row">
            <flux:button variant="primary" type="submit" class="w-full sm:w-auto" data-test="save-goal-button">
                保存する
            </flux:button>
            <flux:button :href="route('dashboard')" class="w-full sm:w-auto" wire:navigate>
                ホームへ戻る
            </flux:button>
        </div>
    </form>
</div>
