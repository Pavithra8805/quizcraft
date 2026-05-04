@extends('layout')

@section('title', 'Start Quiz - QuizCraft')

@section('content')
    <div class="card">
        <h1>🚀 Start Quiz: {{ $quiz->title }}</h1>

        @if($quiz->description)
            <p><strong>Description:</strong> {{ $quiz->description }}</p>
        @endif

        <div style="background: #f0f0f0; padding: 1.5rem; border-radius: 4px; margin: 1.5rem 0;">
            <p><strong>Quiz Details:</strong></p>
            <ul style="margin: 0.5rem 0 0 1.5rem;">
                <li>Total Questions: {{ $quiz->questions->count() }}</li>
                <li>Total Marks: {{ $quiz->getTotalMarks() }}</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('attempts.create', $quiz) }}">
            @csrf

            <div class="form-group">
                <label for="participant_name">Your Name *</label>
                <input type="text" id="participant_name" name="participant_name" value="{{ old('participant_name') }}" required>
            </div>

            <div class="form-group">
                <label for="participant_email">Your Email *</label>
                <input type="email" id="participant_email" name="participant_email" value="{{ old('participant_email') }}" required>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">Start Quiz</button>
                <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
