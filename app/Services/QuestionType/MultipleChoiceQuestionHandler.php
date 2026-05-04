<?php

namespace App\Services\QuestionType;

use App\Models\Question;

class MultipleChoiceQuestionHandler implements QuestionTypeHandlerInterface
{
    /**
     * Validate if answer is array of option IDs
     */
    public function validateAnswer($answer): bool
    {
        if (!is_array($answer)) {
            return false;
        }

        return !empty($answer) && count($answer) > 0;
    }

    /**
     * Evaluate multiple choice answer
     */
    public function evaluateAnswer(Question $question, $answer): bool
    {
        if (!is_array($answer)) {
            return false;
        }

        $correctOptions = $question->options()
            ->where('is_correct', true)
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->toArray();

        $selectedOptions = array_map(fn($id) => (int)$id, $answer);

        sort($correctOptions);
        sort($selectedOptions);

        return $correctOptions === $selectedOptions;
    }

    /**
     * Get correct answers
     */
    public function getCorrectAnswer(Question $question)
    {
        return $question->options()
            ->where('is_correct', true)
            ->get();
    }

    /**
     * Display answers
     */
    public function getAnswerDisplay($answer)
    {
        if (!is_array($answer)) {
            return 'Invalid answer format';
        }

        $options = \App\Models\Option::whereIn('id', $answer)->pluck('option_text')->toArray();
        return !empty($options) ? implode(', ', $options) : 'No options selected';
    }
}
