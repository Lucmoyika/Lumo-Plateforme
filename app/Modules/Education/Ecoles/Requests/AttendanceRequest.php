<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'records'              => ['required', 'array', 'min:1'],
            'records.*.student_id' => ['required', 'exists:students,id'],
            'records.*.class_id'   => ['required', 'exists:school_classes,id'],
            'records.*.date'       => ['required', 'date'],
            'records.*.status'     => ['required', 'string', 'in:present,absent,late,excused'],
            'records.*.note'       => ['nullable', 'string', 'max:255'],
        ];
    }
}
