<?php

namespace App\Models;

use Database\Factories\TermFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $summary
 * @property string $explanation
 * @property string $analogy
 * @property list<string> $points
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuizQuestion|null $quizQuestion
 */
#[Fillable(['slug', 'name', 'summary', 'explanation', 'analogy', 'points', 'sort_order'])]
class Term extends Model
{
    /** @use HasFactory<TermFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasOne<QuizQuestion, $this>
     */
    public function quizQuestion(): HasOne
    {
        return $this->hasOne(QuizQuestion::class);
    }
}
