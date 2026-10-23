<?php

/*
| このアプリで使うルールだけを日本語にしている。ないものは英語（lang/en）で表示される。
*/

return [

    'confirmed' => ':attribute が確認用と一致しません。',
    'current_password' => 'パスワードがちがいます。',
    'email' => ':attribute は正しいメールアドレスの形で入力してください。',
    'exists' => '選んだ :attribute は見つかりません。',
    'lowercase' => ':attribute は小文字で入力してください。',
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'password' => [
        'letters' => ':attribute には英字を1文字以上入れてください。',
        'mixed' => ':attribute には大文字と小文字をそれぞれ1文字以上入れてください。',
        'numbers' => ':attribute には数字を1文字以上入れてください。',
        'symbols' => ':attribute には記号を1文字以上入れてください。',
        'uncompromised' => 'この :attribute は流出したことがあります。別の :attribute にしてください。',
    ],
    'required' => ':attribute を入力してください。',
    'string' => ':attribute は文字で入力してください。',
    'unique' => 'この :attribute はすでに使われています。',

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'current_password' => '現在のパスワード',
        'goal' => '目標',
    ],

];
