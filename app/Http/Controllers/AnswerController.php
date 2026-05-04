<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\Answer;
use App\Http\Requests\SubmitAttemptRequest;
use App\Services\QuizEvaluationService;
use Illuminate\Http\RedirectResponse;

class AnswerController extends Controller
{
    /**
     * Submit quiz answers for evaluation.
     */
    public function submit(SubmitAttemptRequest $request, Quiz $quiz, Attempt $attempt): RedirectResponse
    {
        if ($attempt->is_completed) {
            return redirect()
                ->route('results.show', ['quiz' => $quiz, 'attempt' => $attempt])
                ->with('info', 'This attempt is already completed.');
        }

        $validated = $request->validated();

        // Store all answers
        $answers = $validated['answers'] ?? [];

        foreach ($answers as $questionId => $answer) {
            if ($answer !== null && $answer !== '') {
                Answer::updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                    ],
                    [
                        'answer_text' => is_array($answer) ? null : $answer,
                        'answer_options' => is_array($answer) ? $answer : null,
                    ]
                );
            }
        }

        // Evaluate the attempt
        $evaluationService = new QuizEvaluationService();
        $evaluationService->evaluateAttempt($attempt);

        return redirect()
            ->route('results.show', ['quiz' => $quiz, 'attempt' => $attempt])
            ->with('success', 'Quiz submitted successfully!');
    }
}
