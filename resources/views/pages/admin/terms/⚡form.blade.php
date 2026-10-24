<?php

use App\Models\Term;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

/*
 * 言葉とクイズの追加・編集（同じ画面で扱う）。
 * クイズは問題1つ・選択肢3つ。「正解」は持たず、どの選択肢にも考え方のポイントを書く。
 */
new class extends Component {
    public ?Term $term = null;

    public string $slug = '';
    public string $name = '';
    public string $icon = '';
    public string $summary = '';
    public string $explanation = '';
    public string $analogy = '';

    /** @var list<string> */
    public array $points = ['', '', ''];

    public int $sort_order = 1;

    public string $question = '';

    /** @var list<array{body: string, feedback: string}> */
    public array $choices = [
        ['body' => '', 'feedback' => ''],
        ['body' => '', 'feedback' => ''],
        ['body' => '', 'feedback' => ''],
    ];

    public function mount(?Term $term = null): void
    {
        if ($term === null || ! $term->exists) {
            $this->term = null;
            $this->sort_order = (int) Term::max('sort_order') + 1;

            return;
        }

        $this->term = $term;
        $this->slug = $term->slug;
        $this->name = $term->name;
        $this->icon = $term->icon ?? '';
        $this->summary = $term->summary;
        $this->explanation = $term->explanation;
        $this->analogy = $term->analogy;
        $this->points = array_values(array_pad(array_slice($term->points ?? [], 0, 3), 3, ''));
        $this->sort_order = $term->sort_order;

        $quiz = $term->quizQuestion()->with('choices')->first();

        if ($quiz !== null) {
            $this->question = $quiz->question;

            foreach ($quiz->choices->take(3)->values() as $index => $choice) {
                $this->choices[$index] = ['body' => $choice->body, 'feedback' => $choice->feedback];
            }
        }
    }

    public function render()
    {
        return $this->view()->title($this->term ? '言葉を編集' : '言葉を追加');
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('terms', 'slug')->ignore($this->term?->id)],
            'name' => ['required', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:16'],
            'summary' => ['required', 'string', 'max:100'],
            'explanation' => ['required', 'string', 'max:1000'],
            'analogy' => ['required', 'string', 'max:1000'],
            'points' => ['array', 'size:3'],
            'points.0' => ['required', 'string', 'max:100'],
            'points.1' => ['nullable', 'string', 'max:100'],
            'points.2' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:9999'],
            'question' => ['required', 'string', 'max:500'],
            'choices' => ['array', 'size:3'],
            'choices.*.body' => ['required', 'string', 'max:100'],
            'choices.*.feedback' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'slug' => 'URL用の名前',
            'name' => '言葉',
            'icon' => '絵文字',
            'summary' => 'ひとこと',
            'explanation' => 'やさしい説明',
            'analogy' => '身近なたとえ',
            'points.0' => 'ポイント1',
            'points.1' => 'ポイント2',
            'points.2' => 'ポイント3',
            'sort_order' => '並び順',
            'question' => 'クイズの問題',
            'choices.0.body' => '選択肢A',
            'choices.1.body' => '選択肢B',
            'choices.2.body' => '選択肢C',
            'choices.0.feedback' => '選択肢Aの考え方のポイント',
            'choices.1.feedback' => '選択肢Bの考え方のポイント',
            'choices.2.feedback' => '選択肢Cの考え方のポイント',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'slug.regex' => 'URL用の名前は、半角の小文字・数字・ハイフンで入力してください（例：compound-interest）。',
        ];
    }

    public function save(): void
    {
        $this->authorize('admin');

        $this->validate();

        $term = DB::transaction(function (): Term {
            $term = $this->term ?? new Term;

            $term->fill([
                'slug' => $this->slug,
                'name' => trim($this->name),
                'icon' => trim($this->icon) !== '' ? trim($this->icon) : null,
                'summary' => trim($this->summary),
                'explanation' => trim($this->explanation),
                'analogy' => trim($this->analogy),
                'points' => array_values(array_filter(array_map('trim', $this->points), fn (string $point): bool => $point !== '')),
                'sort_order' => $this->sort_order,
            ])->save();

            $quiz = $term->quizQuestion()->updateOrCreate([], ['question' => trim($this->question)]);

            $quiz->choices()->delete();

            foreach ($this->choices as $index => $choice) {
                $quiz->choices()->create([
                    'body' => trim($choice['body']),
                    'feedback' => trim($choice['feedback']),
                    'sort_order' => $index + 1,
                ]);
            }

            return $term;
        });

        Flux::toast(variant: 'success', text: "「{$term->name}」を保存しました。");

        $this->redirectRoute('admin.terms.index', navigate: true);
    }
}; ?>

<div class="mx-auto flex w-full max-w-3xl flex-col gap-6">
    <div>
        <flux:link :href="route('admin.terms.index')" wire:navigate>← 言葉の管理へ</flux:link>
        <flux:heading size="xl" level="1" class="mt-3 font-heading">
            {{ $term ? '「'.$term->name.'」を編集' : '言葉を追加' }}
        </flux:heading>
        <flux:text class="mt-2">文章は「コンテンツの方針」（やさしく言い換える、短く、商品を勧めない）に沿って書いてください。</flux:text>
    </div>

    <form wire:submit="save" class="flex flex-col gap-8">
        <section class="flex flex-col gap-5 rounded-2xl border border-line bg-card p-6">
            <h2 class="font-heading text-xl font-bold text-ink">言葉</h2>

            <div class="grid gap-5 sm:grid-cols-2">
                <flux:input wire:model="name" label="言葉" placeholder="例：分散投資" />
                <flux:input wire:model="slug" label="URL用の名前（半角英小文字）" placeholder="例：diversification" />
            </div>
            <flux:input wire:model="icon" label="絵文字（一覧と説明に出ます。なくてもよい）" placeholder="例：🧺" class="max-w-60" />
            <flux:input wire:model="summary" label="ひとこと（一覧に出ます）" placeholder="例：お金を、いろいろなところに分けること" />
            <flux:textarea wire:model="explanation" label="やさしい説明（3行くらい）" rows="4" />
            <flux:textarea wire:model="analogy" label="身近なたとえ" rows="3" />

            <div class="flex flex-col gap-3">
                <flux:input wire:model="points.0" label="ポイント1" />
                <flux:input wire:model="points.1" label="ポイント2（なくてもよい）" />
                <flux:input wire:model="points.2" label="ポイント3（なくてもよい）" />
            </div>

            <flux:input wire:model="sort_order" type="number" min="1" label="並び順（小さい順に表示）" class="max-w-40" />
        </section>

        <section class="flex flex-col gap-5 rounded-2xl bg-sky-card p-6">
            <h2 class="font-heading text-xl font-bold text-navy">クイズ</h2>
            <flux:text>「正解」は決めません。どの選択肢を選んでも、その考え方へのひとことを表示します。</flux:text>

            <flux:textarea wire:model="question" label="問題" rows="2" />

            @foreach ($choices as $index => $choice)
                <div wire:key="choice-input-{{ $index }}" class="flex flex-col gap-3 rounded-xl bg-card p-4">
                    <flux:input wire:model="choices.{{ $index }}.body" label="選択肢{{ chr(65 + $index) }}" />
                    <flux:textarea wire:model="choices.{{ $index }}.feedback" label="選んだときの考え方のポイント" rows="2" />
                </div>
            @endforeach
        </section>

        <div class="flex flex-col gap-3 sm:flex-row">
            <flux:button variant="primary" type="submit" class="w-full sm:w-auto" data-test="save-term-button">
                保存する
            </flux:button>
            <flux:button :href="route('admin.terms.index')" class="w-full sm:w-auto" wire:navigate>
                やめる
            </flux:button>
        </div>
    </form>
</div>
