<?php

namespace App\Services\QuestionType;

use App\Models\Question;

class SingleChoiceQuestionHandler implements QuestionTypeHandlerInterface
{
    /**
     * Validate if answer is a valid option ID
     */
    public function validateAnswer($answer): bool
    {
        return is_numeric($answer) && $answer > 0;
    }

    /**
     * Evaluate single choice answer
     */
    public function evaluateAnswer(Question $question, $answer): bool
    {
        $correctOption = $question->options()
            ->where('is_correct', true)
            ->first();

        if (!$correctOption) {
            return false;
        }

        return (int)$answer === (int)$correctOption->id;
    }

    /**
     * Get correct answer
     */
    public function getCorrectAnswer(Question $question)
    {
        return $question->options()
            ->where('is_correct', true)
            ->first();
    }

    /**
     * Display answer
     */
    public function getAnswerDisplay($answer)
    {
        $option = \App\Models\Option::find($answer);
        return $option?->option_text ?? 'Not found';
    }
}
