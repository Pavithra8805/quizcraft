@extends('layout')
@section('title', 'Quizzes - QuizCraft')
@section('content')
<div class="container">
    <div class="card">
        <h1>📚 Quizzes</h1>
        <a href="{{ route('quizzes.create') }}" class="btn btn-primary mb">+ Create New Quiz</a>
        
        @if($quizzes->isEmpty())
            <div class="alert alert-info">No quizzes yet. <a href="{{ route('quizzes.create') }}">Create your first quiz!</a></div>
        @else
            <div class="grid">
                @foreach($quizzes as $quiz)
                    <div class="quiz-card">
                        <h3>{{ $quiz->title }}</h3>
                        <p>{{ Str::limit($quiz->description, 80) }}</p>
                        <p><small>❓ {{ $quiz->questions->count() }} questions | 📊 Total: {{ $quiz->questions->sum('marks') }} marks</small></p>
                        <div class="actions">
                            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-sm btn-secondary">Edit</a>
                            @if($quiz->questions->count() > 0)
                                <a href="{{ route('attempts.start', $quiz) }}" class="btn btn-sm btn-success">Start</a>
                            @endif
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this quiz?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
