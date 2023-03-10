<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IntakeStoreRequest extends FormRequest {
    public function rules(): array {
        return [
            'name'=> [
                'required',
                'string'
            ],
            'code' => [
                'required',
                'unique:intakes,code'
            ],
        ];
    }
}
