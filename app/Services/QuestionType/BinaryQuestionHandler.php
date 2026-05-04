<?php

namespace App\Services\QuestionType;

use App\Models\Question;

class BinaryQuestionHandler implements QuestionTypeHandlerInterface
{
    /**
     * Validate if answer is Yes/No or True/False
     */
    public function validateAnswer($answer): bool
    {
        return in_array(strtolower($answer), ['yes', 'no', 'true', 'false', '1', '0']);
    }

    /**
     * Evaluate binary answer
     */
    public function evaluateAnswer(Question $question, $answer): bool
    {
        $correctOption = $question->options()
            ->where('is_correct', true)
            ->first();

        if (!$correctOption) {
            return false;
        }

        $normalizedAnswer = strtolower($answer);
        $normalizedCorrect = strtolower($correctOption->option_text);

        return $normalizedAnswer === $normalizedCorrect ||
               $normalizedAnswer === substr($normalizedCorrect, 0, 1);
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
        return ucfirst($answer);
    }
}
