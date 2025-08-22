<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasteRequest extends FormRequest
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
            'code' => 'required|max:50000',
            'parent_id' => 'integer|nullable|exists:pastes,id',
        ];
    }
    public function messages(): array
    {
        return [
            'code.required' => 'Code is required.',
            'code.max' => 'Code may not be greater than 50000 characters.',
            'parent_id.integer' => 'Parent ID must be an integer.',
            'parent_id.exists' => 'Parent ID must exist in the pastes table.',
        ];
    }
}
