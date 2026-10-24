<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

/**
 * 今日の格言のたたき台。中身はあとで運営者の言葉に書き直す。
 * 何度実行しても同じ内容になる（格言の文で上書きする）。
 */
class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->quotes() as $order => [$body, $meaning]) {
            Quote::updateOrCreate(
                ['body' => $body],
                ['meaning' => $meaning, 'sort_order' => $order + 1],
            );
        }
    }

    /**
     * @return list<array{0: string, 1: string}>
     */
    private function quotes(): array
    {
        return [
            [
                "木が大きくなるには時間がかかる。\n小さな水やりを続けた人に、大きな実りが訪れる。",
                '相場は、短い期間では下がることもあります。でも、長い時間をかけて続けた人にこそ、育つチャンスが訪れる、という意味です。焦らず、自分のペースで続けることが大切です。',
            ],
            [
                '急がば回れ',
                '早く増やそうと近道をすると、かえって大きく減らしてしまうことがあります。遠回りに見えても、確かな道を一歩ずつ進むほうが、結局は早く着く、という意味です。',
            ],
            [
                '卵はひとつのカゴに盛るな',
                'ひとつのカゴを落とすと、卵は全部割れてしまいます。お金も、いくつかに分けておけば、どこかで失敗しても全部を失わずにすむ、という教えです。',
            ],
            [
                '塵も積もれば山となる',
                'ほんの少しの金額でも、続けて積み重ねると大きな額になります。「少しだから意味がない」と思わず、続けることを大切にしましょう。',
            ],
            [
                '石の上にも三年',
                '冷たい石も、三年座り続ければ温まります。すぐに結果が出なくても、あわてず続けることで、見える景色が変わってくる、という意味です。',
            ],
        ];
    }
}
