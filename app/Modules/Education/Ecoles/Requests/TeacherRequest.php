<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'gender'           => ['nullable', 'in:M,F'],
            'contract_type'    => ['nullable', 'in:annual,semester,temporary'],
            'role'             => ['nullable', 'in:teacher,assistant,substitute'],
            'status'           => ['nullable', 'string', 'in:active,inactive,on_leave'],
        ];
    }

    /**
     * Préparer les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Fournir des valeurs par défaut
        if (!$this->filled('contract_type')) {
            $this->merge(['contract_type' => 'annual']);
        }
        if (!$this->filled('role')) {
            $this->merge(['role' => 'teacher']);
        }
    }

    /**
     * Obtenir les messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'gender.in' => 'Le genre doit être M (homme) ou F (femme)',
            'contract_type.in' => 'Le type de contrat doit être annual, semester ou temporary',
            'role.in' => 'Le rôle doit être teacher (enseignant), assistant ou substitute (remplaçant)',
        ];
    }
}

