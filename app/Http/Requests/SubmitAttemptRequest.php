<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAttemptRequest extends FormRequest
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
            'participant_name' => 'required|string|max:255',
            'participant_email' => 'required|email',
            'answers' => 'required|array',
            'answers.*' => 'nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'participant_name.required' => 'Participant name is required',
            'participant_email.required' => 'Participant email is required',
            'participant_email.email' => 'Please provide a valid email address',
            'answers.required' => 'Answers are required',
        ];
    }
}
