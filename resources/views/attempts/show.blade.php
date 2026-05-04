@extends('layout')
@section('title', 'Quiz Attempt - QuizCraft')
@section('content')
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
            <div>
                <h1>{{ $quiz->title }}</h1>
                <p style="color: #666;">{{ $quiz->description }}</p>
            </div>
            <div style="text-align: right; background: #f9f9f9; padding: 1rem; border-radius: 4px;">
                <p><strong>Participant:</strong> {{ $attempt->participant_name }}</p>
                <p><strong>Email:</strong> {{ $attempt->participant_email }}</p>
                <p style="margin-top: 0.5rem;"><small>Started: {{ $attempt->created_at->format('M d, Y H:i') }}</small></p>
            </div>
        </div>

        <div style="background: #f9f9f9; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
            <p><strong>Progress:</strong> Answer all {{ $quiz->questions->count() }} questions (Total marks: {{ $quiz->questions->sum('marks') }})</p>
        </div>

        <form method="POST" action="{{ route('answers.submit', [$quiz, $attempt]) }}" id="quiz-form">
            @csrf

            @foreach($quiz->questions as $idx => $question)
                <div class="question-block">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <div>
                            <strong style="font-size: 1.1em;">Question {{ $idx + 1 }} of {{ $quiz->questions->count() }}</strong>
                            <span class="badge badge-primary" style="margin-left: 1rem;">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                            <span style="color: #667eea; font-weight: 600; margin-left: 0.5rem;">{{ $question->marks }} mark(s)</span>
                        </div>
                    </div>

                    <h3 style="margin-bottom: 1rem; color: #333;">{{ $question->title }}</h3>

                    @if($question->image_path)
                        <div style="margin-bottom: 1rem;">
                            <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question image" style="max-width: 400px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        </div>
                    @endif

                    @if($question->video_url)
                        <div style="margin-bottom: 1rem;">
                            <p><strong>📹 Video:</strong> <a href="{{ $question->video_url }}" target="_blank" class="btn btn-sm btn-secondary">Watch Video</a></p>
                        </div>
                    @endif

                    <div style="margin-top: 1.5rem; padding: 1rem; background: white; border-radius: 4px;">
                        @if($question->question_type === 'binary')
                            <div style="display: flex; gap: 2rem;">
                                <label style="cursor: pointer; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px; width: 150px; text-align: center; transition: all 0.2s;">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="true" style="margin-right: 0.5rem;"> Yes / True
                                </label>
                                <label style="cursor: pointer; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px; width: 150px; text-align: center; transition: all 0.2s;">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="false" style="margin-right: 0.5rem;"> No / False
                                </label>
                            </div>

                        @elseif($question->question_type === 'single_choice')
                            @foreach($question->options as $option)
                                <label style="display: block; margin-bottom: 0.75rem; padding: 1rem; border: 2px solid #ddd; border-radius: 4px; cursor: pointer; transition: all 0.2s;" onchange="this.style.borderColor='#667eea'; this.style.background='#f9f9f9';">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" style="margin-right: 0.5rem;">
                                    <strong>{{ $option->option_text }}</strong>
                                    @if($option->option_image)
                                        <br><img src="{{ asset('storage/' . $option->option_image) }}" alt="Option" style="max-width: 120px; margin-top: 0.5rem; border-radius: 2px;">
                                    @endif
                                </label>
                            @endforeach

                        @elseif($question->question_type === 'multiple_choice')
                            @foreach($question->options as $option)
                                <label style="display: block; margin-bottom: 0.75rem; padding: 1rem; border: 2px solid #ddd; border-radius: 4px; cursor: pointer; transition: all 0.2s;">
                                    <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}" style="margin-right: 0.5rem;">
                                    <strong>{{ $option->option_text }}</strong>
                                    @if($option->option_image)
                                        <br><img src="{{ asset('storage/' . $option->option_image) }}" alt="Option" style="max-width: 120px; margin-top: 0.5rem; border-radius: 2px;">
                                    @endif
                                </label>
                            @endforeach

                        @elseif($question->question_type === 'number_input')
                            <input type="number" name="answers[{{ $question->id }}]" placeholder="Enter your numerical answer" step="any" style="max-width: 300px; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px;">

                        @elseif($question->question_type === 'text_input')
                            <input type="text" name="answers[{{ $question->id }}]" placeholder="Enter your text answer" style="max-width: 400px; padding: 0.75rem; border: 2px solid #ddd; border-radius: 4px;">
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="btn-group mt" style="justify-content: center;">
                <button type="submit" class="btn btn-success" style="font-size: 1.1em; padding: 1rem 2rem;">✓ Submit Quiz</button>
                <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
