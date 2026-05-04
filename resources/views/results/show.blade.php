@extends('layout')
@section('title', 'Results - QuizCraft')
@section('content')
<div class="container">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 0.5rem;">📊 Quiz Results</h1>

        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
            <h2 style="color: white; margin: 0 0 1rem 0;">{{ $quiz->title }}</h2>
            <p style="margin: 0.5rem 0;"><strong>Participant:</strong> {{ $attempt->participant_name }} <br><small>{{ $attempt->participant_email }}</small></p>
            <p style="margin: 0.5rem 0;"><small>Completed on {{ $attempt->completed_at->format('M d, Y \a\t h:i A') }}</small></p>
        </div>

        <!-- Score Summary -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="score-display" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; padding: 1.5rem; text-align: center;">
                <div class="score-label">Score</div>
                <div class="score-value">{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 8px; padding: 1.5rem; text-align: center;">
                <div class="score-label">Percentage</div>
                <div class="score-value">{{ round(($attempt->obtained_marks / $attempt->total_marks) * 100) }}%</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; border-radius: 8px; padding: 1.5rem; text-align: center;">
                <div class="score-label">Correct Answers</div>
                <div class="score-value">{{ $attempt->answers->where('is_correct', true)->count() }}/{{ $attempt->answers->count() }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border-radius: 8px; padding: 1.5rem; text-align: center;">
                <div class="score-label">Duration</div>
                <div class="score-value">{{ round(($attempt->completed_at->diffInMinutes($attempt->created_at))) }} min</div>
            </div>
        </div>

        <!-- Detailed Review -->
        <h2 style="border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-top: 2rem;">Detailed Review</h2>

        @php
            $questionIndex = 0;
        @endphp

        @foreach($quiz->questions as $question)
            @php
                $answer = $attempt->answers->where('question_id', $question->id)->first();
                $isCorrect = $answer ? $answer->is_correct : false;
                $questionIndex++;
            @endphp

            <div class="question-block {{ $isCorrect ? 'answer-correct' : 'answer-incorrect' }}" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <h3 style="margin: 0 0 0.5rem 0; color: #333;">Question {{ $questionIndex }}</h3>
                        <p style="margin: 0.5rem 0; color: #666;"><strong>{{ $question->title }}</strong></p>
                    </div>
                    <div style="text-align: right;">
                        @if($isCorrect)
                            <span class="badge badge-success" style="font-size: 1em; padding: 0.5rem 1rem;">✓ Correct</span>
                            <p style="font-size: 1.5em; font-weight: bold; color: #28a745; margin: 0.5rem 0;">+{{ $question->marks }} marks</p>
                        @else
                            <span class="badge badge-danger" style="font-size: 1em; padding: 0.5rem 1rem;">✗ Incorrect</span>
                            <p style="font-size: 1.5em; font-weight: bold; color: #dc3545; margin: 0.5rem 0;">0/{{ $question->marks }} marks</p>
                        @endif
                    </div>
                </div>

                @if($question->image_path)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question" style="max-width: 250px; border-radius: 4px;">
                    </div>
                @endif

                <div style="background: white; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
                    <p style="margin-bottom: 0.5rem;"><strong>Your Answer:</strong> 
                        <span style="color: {{ $isCorrect ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                            {{ $answer ? $answer->answer_value : '(Not answered)' }}
                        </span>
                    </p>
                    @if(!$isCorrect)
                        <p style="margin: 0.5rem 0;"><strong>Correct Answer:</strong> 
                            <span style="color: #28a745; font-weight: 600;">
                                @if($question->question_type === 'number_input')
                                    {{ $question->metadata['correct_value'] ?? 'N/A' }} (±{{ $question->metadata['tolerance'] ?? 0 }})
                                @elseif($question->question_type === 'text_input')
                                    {{ $question->metadata['correct_text'] ?? 'N/A' }}
                                @else
                                    {{ $question->options->where('is_correct', true)->pluck('option_text')->implode(', ') }}
                                @endif
                            </span>
                        </p>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Actions -->
        <div class="btn-group" style="justify-content: center; margin-top: 2rem;">
            <a href="{{ route('attempts.list', $quiz) }}" class="btn btn-secondary">View All Attempts</a>
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-secondary">Quiz Details</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-primary">Back to Quizzes</a>
        </div>
    </div>
</div>
@endsection
