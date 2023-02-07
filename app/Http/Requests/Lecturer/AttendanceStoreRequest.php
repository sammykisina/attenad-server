<?php

declare(strict_types=1);

namespace App\Http\Requests\Lecturer;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceStoreRequest extends FormRequest {
    public function rules(): array {
        return [
            'name' => [
                'required',
                'string'
            ],
            'week' => [
                'required',
                'string',
            ],
            'contentDeliveryType' => [
                'required',
                'string'
            ],
            'tutorialGroup' => [
                'string'
            ],
            'intakeId' => [
                'required',
                'exists:intakes,id'
            ],
            'courseId' => [
                'required',
                'exists:courses,id'
            ],
            'moduleId' => [
                'required',
                'exists:modules,id'
            ]
        ];
    }
}
