# ARCHITECTURE.md - QuizCraft Dynamic Quiz System

## Overview

QuizCraft is designed with **extensibility** as the core principle. The architecture avoids hardcoded logic for question types, making it easy to add new question types in the future without modifying existing code.

## Architecture Principles

### 1. Strategy Pattern for Question Type Handling

The system uses the **Strategy Pattern** to handle different question types. Each question type has its own handler class implementing a common interface.

```
QuestionTypeHandlerInterface (Contract)
    ├── BinaryQuestionHandler
    ├── SingleChoiceQuestionHandler
    ├── MultipleChoiceQuestionHandler
    ├── NumberInputQuestionHandler
    └── TextInputQuestionHandler
```

**Benefits:**
- No hardcoded if/else logic across controllers
- Easy to add new question types
- Each handler encapsulates its own validation and evaluation logic
- Follows Open/Closed Principle (open for extension, closed for modification)

### 2. Service Layer Architecture

The application separates concerns into layers:

```
Controllers (HTTP Orchestration)
    ↓
Services (Business Logic)
    ├── QuizEvaluationService
    └── QuestionType Handlers
        ↓
Models (Data Access & Relationships)
    ├── Quiz
    ├── Question
    ├── Option
    ├── Attempt
    └── Answer
```

**Benefits:**
- Controllers remain thin and focused
- Business logic is testable and reusable
- Models handle database relationships only
- Easy to modify evaluation rules without touching controllers

### 3. Database Design

#### Entity Relationships

```
quizzes (1) ──→ (many) questions
  ├── questions (1) ──→ (many) options
  └── quizzes (1) ──→ (many) attempts
        └── attempts (1) ──→ (many) answers
              ├── answers (many) ──→ (1) questions
              └── answers (many) ──→ (1) attempts
```

#### Table Schema

**quizzes**
- id (PK)
- title
- description
- timestamps

**questions**
- id (PK)
- quiz_id (FK)
- type (binary, single_choice, multiple_choice, number_input, text_input)
- question_text
- question_html (for rich text)
- image_path
- video_url
- marks
- order (for question sequencing)
- timestamps

**options**
- id (PK)
- question_id (FK)
- option_text
- option_image
- is_correct
- order
- timestamps

**attempts**
- id (PK)
- quiz_id (FK)
- participant_name
- participant_email
- total_marks
- obtained_marks
- is_completed
- started_at
- completed_at
- timestamps

**answers**
- id (PK)
- attempt_id (FK)
- question_id (FK)
- answer_text (for single text/number answer)
- answer_options (JSON, for multiple choice)
- is_correct
- marks_obtained
- timestamps

### 4. File Organization

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── QuizController.php          (CRUD for quizzes)
│   │   ├── QuestionController.php      (CRUD for questions with media handling)
│   │   ├── AttemptController.php       (Start attempt, list attempts)
│   │   ├── AnswerController.php        (Submit answers for evaluation)
│   │   └── ResultController.php        (Display results)
│   └── Requests/
│       ├── StoreQuizRequest.php        (Quiz validation)
│       ├── StoreQuestionRequest.php    (Question & option validation)
│       └── SubmitAttemptRequest.php    (Attempt submission validation)
├── Models/
│   ├── Quiz.php                        (Relationships: questions, attempts)
│   ├── Question.php                    (Relationships: quiz, options, answers)
│   ├── Option.php                      (Relationship: question)
│   ├── Attempt.php                     (Relationships: quiz, answers)
│   └── Answer.php                      (Relationships: attempt, question)
└── Services/
    ├── QuestionType/
    │   ├── QuestionTypeHandlerInterface.php
    │   ├── BinaryQuestionHandler.php
    │   ├── SingleChoiceQuestionHandler.php
    │   ├── MultipleChoiceQuestionHandler.php
    │   ├── NumberInputQuestionHandler.php
    │   └── TextInputQuestionHandler.php
    └── QuizEvaluationService.php       (Orchestrates evaluation)

database/
└── migrations/
    ├── create_quizzes_table.php
    ├── create_questions_table.php
    ├── create_options_table.php
    ├── create_attempts_table.php
    └── create_answers_table.php

resources/views/
├── layout.blade.php                    (Master layout)
├── quizzes/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
├── questions/
│   ├── create.blade.php
│   └── edit.blade.php
├── attempts/
│   ├── start.blade.php
│   ├── show.blade.php
│   └── list.blade.php
└── results/
    └── show.blade.php

routes/
└── web.php                             (All routes)
```

## How to Add a New Question Type

### Step 1: Update Type Constant
Add the new type to the `QuestionTypeHandlerInterface` contract and type list.

### Step 2: Create Handler Class
Create a new handler in `app/Services/QuestionType/` implementing `QuestionTypeHandlerInterface`:

```php
<?php

namespace App\Services\QuestionType;

use App\Models\Question;

class NewTypeQuestionHandler implements QuestionTypeHandlerInterface
{
    public function validateAnswer($answer): bool
    {
        // Implement validation logic
    }

    public function evaluateAnswer(Question $question, $answer): bool
    {
        // Implement evaluation logic
    }

    public function getCorrectAnswer(Question $question)
    {
        // Return correct answer
    }

    public function getAnswerDisplay($answer)
    {
        // Format answer for display
    }
}
```

### Step 3: Register Handler in Question Model
Update the handler registry in `Question::getHandler()`:

```php
public function getHandler()
{
    $handlers = [
        'binary' => BinaryQuestionHandler::class,
        'single_choice' => SingleChoiceQuestionHandler::class,
        // ... existing handlers
        'new_type' => NewTypeQuestionHandler::class,  // Add here
    ];
    // ... rest of code
}
```

### Step 4: Update Validation
Update `StoreQuestionRequest` to include the new type in validation:

```php
public function rules(): array
{
    return [
        'type' => 'required|in:binary,single_choice,multiple_choice,number_input,text_input,new_type',
        // ... rest of rules
    ];
}
```

### Step 5: Add UI in Views
Update question editor and attempt views to render the new question type:

```blade
@elseif($question->type === 'new_type')
    <!-- Your custom HTML for the new type -->
@endif
```

**No controller changes required!** The evaluation pipeline automatically handles the new type through the handler interface.

## Key Design Patterns Used

### 1. Strategy Pattern
- **Purpose:** Encapsulate algorithms in separate classes
- **Usage:** QuestionTypeHandler implementations
- **Benefits:** Easy to add new types, no hardcoding

### 2. Service Layer Pattern
- **Purpose:** Centralize business logic
- **Usage:** QuizEvaluationService
- **Benefits:** Reusability, testability, separation of concerns

### 3. Repository Pattern (via Eloquent)
- **Purpose:** Abstract data access
- **Usage:** Eloquent models
- **Benefits:** Clean data layer, easy migrations

### 4. Factory Pattern (implicit)
- **Purpose:** Create handler instances based on type
- **Usage:** `Question::getHandler()`
- **Benefits:** Centralized object creation

## Evaluation Logic Flow

```
User submits quiz
    ↓
AnswerController::submit()
    ↓
Store answers in database
    ↓
QuizEvaluationService::evaluateAttempt()
    ├── For each question:
    │   ├── Get Question
    │   ├── Get Question Handler
    │   ├── Handler::evaluateAnswer()
    │   └── Store result in Answer
    │
    ├── Calculate total marks
    └── Update attempt status
    ↓
ResultController::show()
    ↓
Display results with detailed breakdown
```

## Media Handling

### Image Upload
- Images stored in `storage/app/public/questions/` and `storage/app/public/options/`
- Served via public disk symlink
- Max file size: 2MB
- Accepted formats: JPEG, PNG, GIF

### Video URL
- Stored as URL string in database
- Rendered as link in quiz attempt view
- Can be YouTube, Vimeo, or any other video platform URL

## Security Considerations

1. **Input Validation:** All inputs validated via Form Requests
2. **Authorization:** Can be added via Policies/Gates if needed
3. **File Upload:** Type and size restrictions enforced
4. **SQL Injection:** Protected by Eloquent ORM parameterized queries
5. **CSRF Protection:** Enabled by default in Laravel

## Future Enhancement Opportunities

1. **Caching:** Add Redis caching for quiz data
2. **Async Evaluation:** Use queues for large-scale evaluations
3. **Analytics:** Track attempt patterns, common mistakes
4. **Permissions:** Add role-based access (admin, teacher, student)
5. **Question Bank:** Share questions across multiple quizzes
6. **Timer:** Add time limits to quizzes
7. **Randomization:** Shuffle question and option order
8. **Partial Marking:** Award partial marks for multiple choice
9. **Review Mode:** Allow reviewing answers before submission
10. **Export:** PDF/Excel export of results

## Performance Notes

- Eager loading used to prevent N+1 queries
- Pagination implemented for large result sets
- Database indexes recommended on frequently queried fields (quiz_id, question_id, is_completed)
- Consider caching quiz structure for read-heavy workloads

## Testing Strategy

**Unit Tests:**
- Test each question handler in isolation
- Test evaluation service logic

**Integration Tests:**
- Test complete quiz attempt flow
- Test database relationships

**Feature Tests:**
- Test user interactions (create quiz, attempt quiz, view results)

---

## Conclusion

The QuizCraft architecture prioritizes **extensibility without modification**. By using the Strategy Pattern for question types and a clean service layer, new features can be added with minimal changes to existing code. This design makes the system maintainable, testable, and scalable for production use.
