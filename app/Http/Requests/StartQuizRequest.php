<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email'],
            'questions'  => ['required', 'integer', 'min:1', 'max:50'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'type'       => ['nullable', 'in:multiple,boolean'],
        ];
    }
}
