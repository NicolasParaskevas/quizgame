<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "index"    => "required|integer",
            "question" => "required|string",
            "answer"   => "nullable|string"
        ];
    }
}
