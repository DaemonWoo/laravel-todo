<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'sometimes|required|string||max:255',
            'description' => 'sometimes|nullable|string',
            'status' => ['sometimes', new Enum(TaskStatus::class)],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'status.enum' => 'Invalid status value',
        ];
    }
}
