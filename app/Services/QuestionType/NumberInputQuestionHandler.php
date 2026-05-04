<?php

namespace App\Services\QuestionType;

use App\Models\Question;

class NumberInputQuestionHandler implements QuestionTypeHandlerInterface
{
    /**
     * Validate if answer is a number
     */
    public function validateAnswer($answer): bool
    {
        return is_numeric($answer);
    }

    /**
     * Evaluate number input answer
     */
    public function evaluateAnswer(Question $question, $answer): bool
    {
        // For number input, we check if the answer matches the correct answer stored in option_text
        $correctOption = $question->options()
            ->where('is_correct', true)
            ->first();

        if (!$correctOption) {
            return false;
        }

        $correctValue = (float)$correctOption->option_text;
        $userValue = (float)$answer;

        // Allow small tolerance for floating point comparison
        return abs($correctValue - $userValue) < 0.001;
    }

    /**
     * Get correct answer
     */
    public function getCorrectAnswer(Question $question)
    {
        return $question->options()
            ->where('is_correct', true)
            ->first()
            ?->option_text ?? 'N/A';
    }

    /**
     * Display answer
     */
    public function getAnswerDisplay($answer)
    {
        return (float)$answer;
    }
}
