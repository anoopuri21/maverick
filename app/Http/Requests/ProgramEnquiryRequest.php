<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'programme' => 'nullable|string|max:200',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'country' => 'nullable|string|max:100',
            'study_mode' => 'nullable|string|max:50',
            'qualification' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:2000',
        ];
    }
}
