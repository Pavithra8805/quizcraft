@extends('layout')
@section('title', 'Edit Question - QuizCraft')
@section('content')
<div class="container">
    <div class="card" style="max-width: 800px;">
        <h1>✏️ Edit Question in "{{ $quiz->title }}"</h1>
        
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

        <form method="POST" action="{{ route('questions.update', [$quiz, $question]) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="question_type">Question Type *</label>
                <select id="question_type" name="type" required onchange="updateQuestionType()">
                    <option value="">-- Select Question Type --</option>
                    <option value="binary" {{ old('type', $question->type) == 'binary' ? 'selected' : '' }}>Binary (Yes/No or True/False)</option>
                    <option value="single_choice" {{ old('type', $question->type) == 'single_choice' ? 'selected' : '' }}>Single Choice (One correct)</option>
                    <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice (Multiple correct)</option>
                    <option value="number_input" {{ old('type', $question->type) == 'number_input' ? 'selected' : '' }}>Number Input</option>
                    <option value="text_input" {{ old('type', $question->type) == 'text_input' ? 'selected' : '' }}>Text Input</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Question Text *</label>
                <textarea id="question_text" name="question_text" placeholder="Enter your question here..." required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Question Image (Optional)</label>
                @if($question->image_path)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question image" style="max-width: 200px; border-radius: 4px;">
                        <p><small>Current: {{ $question->image_path }}</small></p>
                    </div>
                @endif
                <input type="file" id="image" name="image" accept="image/*">
                <small>Max 2MB. Formats: JPEG, PNG, GIF (leave empty to keep current)</small>
            </div>

            <div class="form-group">
                <label for="video_url">Video URL (Optional - YouTube, Vimeo, etc.)</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://youtube.com/watch?v=..." value="{{ old('video_url', $question->video_url) }}">
            </div>

            <div class="form-group">
                <label for="marks">Marks *</label>
                <input type="number" id="marks" name="marks" value="{{ old('marks', $question->marks) }}" min="1" max="100" required>
            </div>

            <!-- Options Section (for binary, single choice, multiple choice) -->
            <div id="options-section" style="display: none;">
                <h2>Options</h2>
                <div id="options-container"></div>
                <button type="button" onclick="addOption()" class="btn btn-secondary mb">+ Add Option</button>
            </div>

            <!-- Number Input Section -->
            <div id="number-section" style="display: none;">
                <h2>Answer</h2>
                <div class="form-group">
                    <label for="correct_value">Correct Number *</label>
                    <input type="number" id="correct_value" name="correct_value" placeholder="Enter the correct numerical answer" value="{{ old('correct_value') }}">
                </div>
                <div class="form-group">
                    <label for="tolerance">Tolerance (±) *</label>
                    <input type="number" id="tolerance" name="tolerance" value="{{ old('tolerance', 0) }}" step="0.1" placeholder="Acceptable margin of error">
                </div>
            </div>

            <!-- Text Input Section -->
            <div id="text-section" style="display: none;">
                <h2>Answer</h2>
                <div class="form-group">
                    <label for="correct_text">Correct Answer Text *</label>
                    <input type="text" id="correct_text" name="correct_text" placeholder="Enter the correct text answer" value="{{ old('correct_text') }}">
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="case_sensitive" {{ old('case_sensitive') ? 'checked' : '' }}> Case Sensitive
                    </label>
                </div>
            </div>

            <div class="btn-group mt">
                <button type="submit" class="btn btn-success">✓ Update Question</button>
                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let optionCount = 0;

function updateQuestionType() {
    const type = document.getElementById('question_type').value;
    document.getElementById('options-section').style.display = (type === 'binary' || type === 'single_choice' || type === 'multiple_choice') ? 'block' : 'none';
    document.getElementById('number-section').style.display = type === 'number_input' ? 'block' : 'none';
    document.getElementById('text-section').style.display = type === 'text_input' ? 'block' : 'none';
}

function addOption() {
    const container = document.getElementById('options-container');
    const type = document.getElementById('question_type').value;
    const optionDiv = document.createElement('div');
    optionDiv.style.cssText = 'border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; background: #f9f9f9;';
    optionDiv.innerHTML = `
        <div class="form-group">
            <label>Option ${optionCount + 1} Text</label>
            <input type="text" name="options[${optionCount}][text]" placeholder="Enter option text" required>
        </div>
        <div class="form-group">
            <label>Option ${optionCount + 1} Image (Optional)</label>
            <input type="file" name="options[${optionCount}][image]" accept="image/*">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="options[${optionCount}][is_correct]" ${type === 'single_choice' ? 'onclick="uncheckOthers(this)"' : ''}> 
                This option is correct
            </label>
        </div>
        <button type="button" onclick="this.parentElement.remove(); optionCount--;" class="btn btn-sm btn-danger">Remove</button>
    `;
    container.appendChild(optionDiv);
    optionCount++;
}

function uncheckOthers(checkbox) {
    if (checkbox.checked) {
        document.querySelectorAll('input[name*="[is_correct]"]').forEach(cb => {
            if (cb !== checkbox) cb.checked = false;
        });
    }
}

// Initialize on page load with existing options if they exist
document.addEventListener('DOMContentLoaded', function() {
    updateQuestionType();
    
    // Load existing options if edit mode and options exist
    const questionType = document.getElementById('question_type').value;
    if (questionType === 'binary' || questionType === 'single_choice' || questionType === 'multiple_choice') {
        // Note: In a real implementation, you'd populate existing options from the question data
        // For now, this will just initialize empty state
    }
});
</script>
@endsection
