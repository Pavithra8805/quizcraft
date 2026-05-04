<?php

namespace App\Services\QuestionType;

use App\Models\Question;

class TextInputQuestionHandler implements QuestionTypeHandlerInterface
{
    /**
     * Validate if answer is non-empty text
     */
    public function validateAnswer($answer): bool
    {
        return is_string($answer) && strlen(trim($answer)) > 0;
    }

    /**
     * Evaluate text input answer (case-insensitive comparison)
     */
    public function evaluateAnswer(Question $question, $answer): bool
    {
        $correctOption = $question->options()
            ->where('is_correct', true)
            ->first();

        if (!$correctOption) {
            return false;
        }

        $correctText = strtolower(trim($correctOption->option_text));
        $userText = strtolower(trim($answer));

        return $correctText === $userText;
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
        return trim($answer);
    }
}
