<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $schoolId = (int) $this->route('school');
        $updating = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'user_id'         => [$updating ? 'sometimes' : 'required', 'exists:users,id'],
            'school_id'       => [$updating ? 'sometimes' : ($schoolId > 0 ? 'nullable' : 'required'), 'exists:schools,id'],
            'class_id'        => ['nullable', 'exists:school_classes,id'],
            'student_number'  => ['nullable', 'string', 'max:50'],
            'enrollment_date' => ['nullable', 'date'],
            'parent_id'       => ['nullable', 'exists:users,id'],
            'guardian_name'   => ['nullable', 'string', 'max:100'],
            'guardian_phone'  => ['nullable', 'string', 'max:20'],
            'guardian_email'  => ['nullable', 'email', 'max:255'],
            'status'          => ['nullable', 'string', 'in:active,inactive,graduated,transferred'],
        ];
    }
}
