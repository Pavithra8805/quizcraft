<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Attempt;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AttemptController extends Controller
{
    /**
     * Show quiz start page (enter participant details).
     */
    public function start(Quiz $quiz): View
    {
        $quiz->load('questions.options');

        return view('attempts.start', compact('quiz'));
    }

    /**
     * Create a new attempt and redirect to quiz.
     */
    public function create(Quiz $quiz): RedirectResponse
    {
        $attempt = Attempt::create([
            'quiz_id' => $quiz->id,
            'participant_name' => request('participant_name'),
            'participant_email' => request('participant_email'),
            'started_at' => now(),
            'total_marks' => $quiz->getTotalMarks(),
        ]);

        return redirect()
            ->route('attempts.show', ['quiz' => $quiz, 'attempt' => $attempt]);
    }

    /**
     * Display the quiz attempt interface.
     */
    public function show(Quiz $quiz, Attempt $attempt): View
    {
        if ($attempt->is_completed) {
            return redirect()->route('results.show', ['quiz' => $quiz, 'attempt' => $attempt]);
        }

        $attempt->load('quiz.questions.options', 'answers');

        return view('attempts.show', compact('quiz', 'attempt'));
    }

    /**
     * List all attempts for a quiz.
     */
    public function list(Quiz $quiz): View
    {
        $attempts = $quiz->attempts()
            ->where('is_completed', true)
            ->orderByDesc('completed_at')
            ->paginate(10);

        return view('attempts.list', compact('quiz', 'attempts'));
    }
}
