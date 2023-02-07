<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LecturerStoreRequest extends FormRequest {
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
            'courses' => [
                'array'
            ],
            'intakes' => [
                'array'
            ],
            'modules' => [
                'array'
            ]
        ];
    }
}
