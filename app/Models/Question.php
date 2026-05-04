<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'question_html',
        'image_path',
        'video_url',
        'marks',
        'order'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the question type handler for this question
     */
    public function getHandler()
    {
        $handlers = [
            'binary' => \App\Services\QuestionType\BinaryQuestionHandler::class,
            'single_choice' => \App\Services\QuestionType\SingleChoiceQuestionHandler::class,
            'multiple_choice' => \App\Services\QuestionType\MultipleChoiceQuestionHandler::class,
            'number_input' => \App\Services\QuestionType\NumberInputQuestionHandler::class,
            'text_input' => \App\Services\QuestionType\TextInputQuestionHandler::class,
        ];

        if (!isset($handlers[$this->type])) {
            throw new \Exception("Unknown question type: {$this->type}");
        }

        return new $handlers[$this->type]();
    }
}
