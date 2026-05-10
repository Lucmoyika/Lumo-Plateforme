<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaternelleTeacherRequest extends FormRequest
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
            'user_id'          => [$updating ? 'sometimes' : 'required', 'exists:users,id'],
            'school_id'        => [$updating ? 'sometimes' : ($schoolId > 0 ? 'nullable' : 'required'), 'exists:schools,id'],
            'employee_number'   => ['nullable', 'string', 'max:50'],
            'subjects'          => ['nullable', 'array'],
            'subjects.*'        => ['string', 'max:100'],
            'qualification'    => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'gender'           => ['nullable', 'in:F'],
            'contract_type'    => ['nullable', 'in:annual'],
            'role'             => ['nullable', 'in:teacher,assistant'],
            'status'           => ['nullable', 'string', 'in:active,inactive,on_leave'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('gender')) {
            $this->merge(['gender' => 'F']);
        }
        if (!$this->filled('contract_type')) {
            $this->merge(['contract_type' => 'annual']);
        }
        if (!$this->filled('role')) {
            $this->merge(['role' => 'teacher']);
        }
    }

    public function messages(): array
    {
        return [
            'gender.in' => 'Maternelle accepte UNIQUEMENT les enseignantes (F)',
            'contract_type.in' => 'Maternelle: contrat annuel uniquement',
            'role.in' => 'Rôle invalide pour Maternelle',
        ];
    }
}
