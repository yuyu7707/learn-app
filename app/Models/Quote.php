<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\QuoteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $body
 * @property string $meaning
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['body', 'meaning', 'sort_order'])]
class Quote extends Model
{
    /** @use HasFactory<QuoteFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * その日の格言。並び順に1日1つずつ進み、最後まで行ったら最初に戻る。
     */
    public static function forDate(CarbonInterface $date): ?self
    {
        $quotes = static::query()->orderBy('sort_order')->orderBy('id')->get();

        if ($quotes->isEmpty()) {
            return null;
        }

        $days = (int) floor($date->copy()->startOfDay()->getTimestamp() / 86400);

        return $quotes[$days % $quotes->count()];
    }

    /**
     * 今日（日本時間）の格言。
     */
    public static function today(): ?self
    {
        return static::forDate(now('Asia/Tokyo'));
    }
}
