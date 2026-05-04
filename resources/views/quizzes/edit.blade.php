@extends('layout')
@section('title', 'Edit Quiz - QuizCraft')
@section('content')
<div class="container">
    <div class="card" style="max-width: 600px;">
        <h1>✏️ Edit Quiz</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix these errors:</strong>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.update', $quiz) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="title">Quiz Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter quiz description...">{{ old('description', $quiz->description) }}</textarea>
            </div>

            <div class="form-group">
                <p style="color: #666; margin: 0;"><strong>Stats:</strong></p>
                <p style="color: #666; margin: 0.5rem 0;"><small>Total Questions: {{ $quiz->questions->count() }}</small></p>
                <p style="color: #666; margin: 0;"><small>Total Marks: {{ $quiz->questions->sum('marks') }}</small></p>
            </div>

            <div class="btn-group mt">
                <button type="submit" class="btn btn-success">✓ Update Quiz</button>
                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
