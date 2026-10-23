<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * 運営者を1人作る。メールとパスワードは .env の ADMIN_EMAIL・ADMIN_PASSWORD から読む。
     */
    public function run(): void
    {
        $email = config('learn.admin.email');
        $password = config('learn.admin.password');

        if (! is_string($email) || $email === '' || ! is_string($password) || $password === '') {
            $this->command->warn('ADMIN_EMAIL と ADMIN_PASSWORD が未設定のため、運営者は作りませんでした。');

            return;
        }

        $admin = User::firstOrNew(['email' => $email]);
        $admin->name = (string) config('learn.admin.name');
        $admin->password = $password;
        $admin->role = Role::Admin;
        $admin->save();
    }
}
