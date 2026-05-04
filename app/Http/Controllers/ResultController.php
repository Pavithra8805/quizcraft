<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Attempt;
use App\Services\QuizEvaluationService;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display the quiz result.
     */
    public function show(Quiz $quiz, Attempt $attempt): View
    {
        if (!$attempt->is_completed) {
            return redirect()
                ->route('attempts.show', ['quiz' => $quiz, 'attempt' => $attempt])
                ->with('warning', 'Please complete the quiz first.');
        }

        $attempt->load('answers.question.options');

        $evaluationService = new QuizEvaluationService();
        $report = $evaluationService->getDetailedReport($attempt);

        return view('results.show', compact('quiz', 'attempt', 'report'));
    }
}
