#!/usr/bin/env bash
# 公開版（VPS）を GitHub の main の最新にする。VPS 上で実行する: bash /var/www/learn-app/deploy.sh
# ※ TermSeeder はここでは実行しない（管理画面で直した言葉が初期データで上書きされるため）
set -e

echo "==> アプリのディレクトリへ移動"
cd /var/www/learn-app

echo "==> main ブランチの最新を取得"
git pull origin main

echo "==> Composer の依存をインストール（本番用）"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> フロントエンドをビルド"
npm ci && npm run build

echo "==> マイグレーションを実行"
php artisan migrate --force

echo "==> キャッシュを作成（optimize）"
php artisan optimize

echo "==> デプロイ完了"
