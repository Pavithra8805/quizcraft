@extends('layout')
@section('title', 'Attempts - QuizCraft')
@section('content')
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h1>📈 Quiz Attempts</h1>
            <div style="background: #f9f9f9; padding: 1rem; border-radius: 4px;">
                <p style="margin: 0.25rem 0;"><strong>Quiz:</strong> {{ $quiz->title }}</p>
                <p style="margin: 0.25rem 0;"><small>Total Attempts: {{ $attempts->count() }}</small></p>
            </div>
        </div>

        @if($attempts->isEmpty())
            <div style="text-align: center; padding: 2rem; background: #f9f9f9; border-radius: 4px;">
                <p style="color: #999; font-size: 1.1em;">No attempts found for this quiz yet.</p>
                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary mt">Go Back</a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Participant Name</th>
                            <th>Email</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Duration</th>
                            <th>Completed At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $idx => $attempt)
                            <tr>
                                <td><strong>{{ $idx + 1 }}</strong></td>
                                <td>{{ $attempt->participant_name }}</td>
                                <td><small>{{ $attempt->participant_email }}</small></td>
                                <td>
                                    <strong>{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</strong>
                                </td>
                                <td>
                                    <strong style="color: {{ round(($attempt->obtained_marks / $attempt->total_marks) * 100) >= 50 ? '#28a745' : '#dc3545' }};">
                                        {{ round(($attempt->obtained_marks / $attempt->total_marks) * 100) }}%
                                    </strong>
                                </td>
                                <td>{{ round(($attempt->completed_at->diffInMinutes($attempt->created_at))) }} min</td>
                                <td><small>{{ $attempt->completed_at->format('M d, Y H:i') }}</small></td>
                                <td>
                                    <a href="{{ route('results.show', [$quiz, $attempt]) }}" class="btn btn-sm btn-primary">View Results</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="btn-group mt">
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-secondary">← Back to Quiz</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Back to Quizzes</a>
        </div>
    </div>
</div>
@endsection
