<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaternelleClassRequest extends FormRequest
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
            'level'          => [$updating ? 'sometimes' : 'required', 'string', 'in:1er,2e,3e'],
            'teacher_id'     => ['nullable', 'exists:users,id'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
            'academic_year'  => [$updating ? 'sometimes' : 'required', 'string', 'max:20'],
            'room'           => ['nullable', 'string', 'max:100'],
            'status'         => ['nullable', 'string', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'level.in' => 'Le niveau doit être: 1er, 2e ou 3e (Maternelle a 3 niveaux seulement)',
        ];
    }
}
