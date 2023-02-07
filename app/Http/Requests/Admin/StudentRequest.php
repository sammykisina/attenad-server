<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest {
    public function rules(): array {
        return [
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string'
            ],
            'role' => [
                'string',
                'required'
            ],
            'physicalCardId' => [
                'string',
                'required'
            ],
            'course_id' => [
                'exists:courses,id'
            ],
            'intake_id' => [
                'exists:intakes,id'
            ]
        ];
    }
}
