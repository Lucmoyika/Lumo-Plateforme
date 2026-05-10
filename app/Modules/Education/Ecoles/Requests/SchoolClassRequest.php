<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolClassRequest extends FormRequest
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
            'school_id'      => [$updating ? 'sometimes' : ($schoolId > 0 ? 'nullable' : 'required'), 'exists:schools,id'],
            'name'           => [$updating ? 'sometimes' : 'required', 'string', 'max:100'],
            'level'          => [$updating ? 'sometimes' : 'required', 'string', 'max:50'],
            'class_variant'  => ['nullable', 'string', 'max:10', 'regex:/^[A-Z]$/'], // A, B, C, etc.
            'education_submodule' => ['nullable', 'string', 'in:mp,ps,sh,full'],
            'teacher_id'     => ['nullable', 'exists:users,id'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
            'academic_year'  => [$updating ? 'sometimes' : 'required', 'string', 'max:20'],
            'room'           => ['nullable', 'string', 'max:100'],
            'status'         => ['nullable', 'string', 'in:active,inactive'],
            'schedules'      => ['sometimes', 'array'],
            'schedules.*.day' => ['required_with:schedules', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'schedules.*.start' => ['required_with:schedules', 'date_format:H:i'],
            'schedules.*.end' => ['required_with:schedules', 'date_format:H:i'],
            'schedules.*.subject' => ['required_with:schedules', 'string', 'max:100'],
            'schedules.*.teacher_id' => ['nullable', 'exists:users,id'],
            'schedules.*.room' => ['nullable', 'string', 'max:100'],
            'schedules.*.color' => ['nullable', 'string', 'max:20'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('school_year') && !$this->filled('academic_year')) {
            $this->merge(['academic_year' => $this->input('school_year')]);
        }

        if ($this->filled('capacity') && !$this->filled('max_students')) {
            $this->merge(['max_students' => $this->input('capacity')]);
        }
    }

    /**
     * Obtenir les messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'class_variant.regex' => 'La variante de classe doit être une lettre majuscule (A, B, C, etc.)',
            'education_submodule.in' => 'Le sous-module d\'éducation doit être mp, ps, sh ou full',
        ];
    }
}

