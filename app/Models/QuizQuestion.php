<?php

namespace App\Models;

use Database\Factories\QuizQuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $term_id
 * @property string $question
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Term $term
 * @property-read Collection<int, QuizChoice> $choices
 */
#[Fillable(['term_id', 'question'])]
class QuizQuestion extends Model
{
    /** @use HasFactory<QuizQuestionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Term, $this>
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * @return HasMany<QuizChoice, $this>
     */
    public function choices(): HasMany
    {
        return $this->hasMany(QuizChoice::class)->orderBy('sort_order');
    }
}
