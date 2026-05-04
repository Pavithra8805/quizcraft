<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_text',
        'answer_options',
        'is_correct',
        'marks_obtained'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'answer_options' => 'array',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
