<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Http\Requests\StoreQuizRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes.
     */
    public function index(): View
    {
        $quizzes = Quiz::withCount('questions')
            ->withCount('attempts')
            ->latest()
            ->paginate(10);

        return view('quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(): View
    {
        return view('quizzes.create');
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(StoreQuizRequest $request): RedirectResponse
    {
        $quiz = Quiz::create($request->validated());

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully! Now add questions.');
    }

    /**
     * Display the specified quiz.
     */
    public function show(Quiz $quiz): View
    {
        $quiz->load('questions.options');

        return view('quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz): View
    {
        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(StoreQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $quiz->update($request->validated());

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully!');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()
            ->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }
}
