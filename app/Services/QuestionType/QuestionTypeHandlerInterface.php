<?php

namespace App\Services\QuestionType;

use App\Models\Question;
use App\Models\Answer;

interface QuestionTypeHandlerInterface
{
    /**
     * Validate user answer for this question type
     */
    public function validateAnswer($answer): bool;

    /**
     * Evaluate if the answer is correct
     */
    public function evaluateAnswer(Question $question, $answer): bool;

    /**
     * Get the correct answer(s) for display
     */
    public function getCorrectAnswer(Question $question);

    /**
     * Get answer display format
     */
    public function getAnswerDisplay($answer);
}
