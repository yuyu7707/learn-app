<?php

namespace App\Models;

use Database\Factories\QuizChoiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $quiz_question_id
 * @property string $body
 * @property string $feedback
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuizQuestion $question
 */
#[Fillable(['quiz_question_id', 'body', 'feedback', 'sort_order'])]
class QuizChoice extends Model
{
    /** @use HasFactory<QuizChoiceFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<QuizQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
