@extends('layout')
@section('title', 'Create Quiz - QuizCraft')
@section('content')
<div class="container">
    <div class="card">
        <h1>✏️ Create New Quiz</h1>
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
        <form method="POST" action="{{ route('quizzes.store') }}">
            @csrf
            <div class="form-group">
                <label for="title">Quiz Title *</label>
                <input type="text" id="title" name="title" placeholder="e.g., JavaScript Fundamentals" value="{{ old('title') }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" placeholder="Describe what this quiz covers...">{{ old('description') }}</textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-success">Create Quiz</button>
                <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
