<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    /**
     * Show form to create a new question.
     */
    public function create(Quiz $quiz): View
    {
        $questionTypes = [
            'binary' => 'Binary (Yes/No or True/False)',
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'number_input' => 'Number Input',
            'text_input' => 'Text Input',
        ];

        return view('questions.create', compact('quiz', 'questionTypes'));
    }

    /**
     * Store a newly created question.
     */
    public function store(StoreQuestionRequest $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('questions', 'public');
        }

        $validated['quiz_id'] = $quiz->id;

        $question = Question::create($validated);

        // Handle options
        if ($request->has('options')) {
            foreach ($request->options as $index => $option) {
                $optionData = [
                    'question_id' => $question->id,
                    'option_text' => $option['text'] ?? null,
                    'is_correct' => isset($option['is_correct']) && $option['is_correct'] == true,
                    'order' => $index,
                ];

                // Handle option image
                if ($request->hasFile("options.{$index}.image")) {
                    $optionData['option_image'] = $request->file("options.{$index}.image")
                        ->store('options', 'public');
                }

                $question->options()->create($optionData);
            }
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Question added successfully!');
    }

    /**
     * Show the form for editing a question.
     */
    public function edit(Quiz $quiz, Question $question): View
    {
        $questionTypes = [
            'binary' => 'Binary (Yes/No or True/False)',
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'number_input' => 'Number Input',
            'text_input' => 'Text Input',
        ];

        $question->load('options');

        return view('questions.edit', compact('quiz', 'question', 'questionTypes'));
    }

    /**
     * Update the specified question.
     */
    public function update(StoreQuestionRequest $request, Quiz $quiz, Question $question): RedirectResponse
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('questions', 'public');
        }

        $question->update($validated);

        // Update options
        if ($request->has('options')) {
            $question->options()->delete();

            foreach ($request->options as $index => $option) {
                $optionData = [
                    'question_id' => $question->id,
                    'option_text' => $option['text'] ?? null,
                    'is_correct' => isset($option['is_correct']) && $option['is_correct'] == true,
                    'order' => $index,
                ];

                if ($request->hasFile("options.{$index}.image")) {
                    $optionData['option_image'] = $request->file("options.{$index}.image")
                        ->store('options', 'public');
                }

                $question->options()->create($optionData);
            }
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Delete the specified question.
     */
    public function destroy(Quiz $quiz, Question $question): RedirectResponse
    {
        // Delete associated images
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }

        foreach ($question->options as $option) {
            if ($option->option_image) {
                Storage::disk('public')->delete($option->option_image);
            }
        }

        $question->delete();

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Question deleted successfully!');
    }
}
