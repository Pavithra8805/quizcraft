<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Question;
use App\Models\Answer;

class QuizEvaluationService
{
    /**
     * Evaluate a complete quiz attempt
     */
    public function evaluateAttempt(Attempt $attempt): array
    {
        $quiz = $attempt->quiz;
        $totalMarks = 0;
        $obtainedMarks = 0;

        $questions = $quiz->questions()->get();

        foreach ($questions as $question) {
            $totalMarks += $question->marks;

            // Find the answer for this question
            $answer = $attempt->answers()
                ->where('question_id', $question->id)
                ->first();

            if ($answer) {
                $isCorrect = $this->evaluateAnswer($question, $answer);
                $marksForQuestion = $isCorrect ? $question->marks : 0;

                $answer->update([
                    'is_correct' => $isCorrect,
                    'marks_obtained' => $marksForQuestion,
                ]);

                $obtainedMarks += $marksForQuestion;
            }
        }

        // Update attempt with final scores
        $attempt->update([
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        return [
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'percentage' => $this->calculatePercentage($obtainedMarks, $totalMarks),
            'attempt' => $attempt,
        ];
    }

    /**
     * Evaluate a single answer
     */
    public function evaluateAnswer(Question $question, Answer $answer): bool
    {
        $handler = $question->getHandler();

        $userAnswer = $answer->answer_text ?? $answer->answer_options;

        return $handler->evaluateAnswer($question, $userAnswer);
    }

    /**
     * Calculate percentage score
     */
    public function calculatePercentage(int $obtained, int $total): float
    {
        if ($total == 0) {
            return 0;
        }

        return round(($obtained / $total) * 100, 2);
    }

    /**
     * Get detailed report for an attempt
     */
    public function getDetailedReport(Attempt $attempt): array
    {
        $report = [
            'attempt_id' => $attempt->id,
            'participant_name' => $attempt->participant_name,
            'participant_email' => $attempt->participant_email,
            'quiz_title' => $attempt->quiz->title,
            'total_marks' => $attempt->total_marks,
            'obtained_marks' => $attempt->obtained_marks,
            'percentage' => $attempt->getPercentage(),
            'duration_minutes' => $attempt->getDurationMinutes(),
            'questions' => [],
        ];

        foreach ($attempt->answers as $answer) {
            $question = $answer->question;
            $handler = $question->getHandler();

            $report['questions'][] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->type,
                'marks' => $question->marks,
                'user_answer' => $handler->getAnswerDisplay($answer->answer_text ?? $answer->answer_options),
                'correct_answer' => $handler->getCorrectAnswer($question),
                'is_correct' => $answer->is_correct,
                'marks_obtained' => $answer->marks_obtained,
            ];
        }

        return $report;
    }
}
