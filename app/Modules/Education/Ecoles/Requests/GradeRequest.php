<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'class_id'   => ['required', 'exists:school_classes,id'],
            'subject'    => ['required', 'string', 'max:100'],
            'value'      => ['required', 'numeric', 'min:0', 'max:20'],
            'period'     => ['required', 'string', 'max:50'],
            'type'       => ['nullable', 'string', 'in:exam,quiz,homework,participation'],
            'comment'    => ['nullable', 'string', 'max:500'],
        ];
    }
}
