<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HumanitesClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_id'      => ['required_without:school', 'exists:schools,id'],
            'name'           => ['required', 'string', 'max:100'],
            'level'          => ['required', 'string', 'in:5e,6e'],
            'class_variant'  => ['nullable', 'string', 'regex:/^[A-Z]$/'],
            'teacher_id'     => ['nullable', 'exists:users,id'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
            'academic_year'  => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'level.in' => 'Humanités: niveaux = 5e, 6e',
        ];
    }
}
