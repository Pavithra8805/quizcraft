@extends('layout')
@section('title', $quiz->title . ' - QuizCraft')
@section('content')
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
            <div>
                <h1>{{ $quiz->title }}</h1>
                <p style="color: #666;">{{ $quiz->description }}</p>
            </div>
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">← Back</a>
        </div>

        <div style="background: #f9f9f9; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
            <p><strong>Total Questions:</strong> {{ $quiz->questions->count() }} | <strong>Total Marks:</strong> {{ $quiz->questions->sum('marks') }}</p>
        </div>

        <div class="btn-group mb">
            <a href="{{ route('questions.create', $quiz) }}" class="btn btn-primary">+ Add Question</a>
            <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-secondary">Edit Quiz</a>
            @if($quiz->questions->count() > 0)
                <a href="{{ route('attempts.start', $quiz) }}" class="btn btn-success">Start Quiz</a>
                <a href="{{ route('attempts.list', $quiz) }}" class="btn btn-secondary">View Attempts</a>
            @endif
        </div>

        <h2>Questions</h2>

        @if($quiz->questions->isEmpty())
            <div class="alert alert-info" style="text-align: center; padding: 2rem;">
                <p style="margin-bottom: 1rem;">📝 No questions added yet.</p>
                <a href="{{ route('questions.create', $quiz) }}" class="btn btn-primary">Add Your First Question</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Type</th>
                        <th>Marks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quiz->questions as $question)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($question->title, 50) }}</strong>
                                @if($question->image_path)
                                    <br><small>📷 Has image</small>
                                @endif
                                @if($question->video_url)
                                    <br><small>🎥 Has video</small>
                                @endif
                            </td>
                            <td><span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span></td>
                            <td><strong>{{ $question->marks }}</strong></td>
                            <td>
                                <a href="{{ route('questions.edit', [$quiz, $question]) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('questions.destroy', [$quiz, $question]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this question?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

        <div style="margin-top: 2rem;">
            <a href="{{ route('questions.create', $quiz) }}" class="btn btn-success">➕ Add Question</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Back to Quizzes</a>
        </div>
    </div>
@endsection
