<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:binary,single_choice,multiple_choice,number_input,text_input',
            'question_text' => 'required|string',
            'question_html' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'marks' => 'required|integer|min:1',
            'options' => 'required|array',
            'options.*.text' => 'nullable|string',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options.*.is_correct' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Question type is required',
            'type.in' => 'Invalid question type selected',
            'question_text.required' => 'Question text is required',
            'marks.required' => 'Marks are required',
            'marks.min' => 'Marks must be at least 1',
            'options.required' => 'At least one option is required',
        ];
    }
}
