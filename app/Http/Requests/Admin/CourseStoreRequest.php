<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseStoreRequest extends FormRequest {
    public function rules(): array {
        return [
            'name'=> [
                'required',
                'string'
            ],
            'code' => [
                'required',
                'unique:courses,code'
            ],
        ];
    }
}
