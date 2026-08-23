<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MbaMastersLandingEnquiryRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'country' => 'nullable|string|max:100',
            'program' => 'nullable|string|max:120',
            'specialization' => 'nullable|string|max:120',
            'qualification' => 'nullable|string|max:50',
            'start_timeline' => [
                'nullable',
                'string',
                Rule::in(['1-3-months', '3-6-months', 'more-than-6-months', 'not-decided']),
            ],
            'website' => 'nullable|string|max:100',
        ];
    }
}
