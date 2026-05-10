<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecondaireTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'          => ['required', 'exists:users,id'],
            'school_id'        => ['required_without:school', 'exists:schools,id'],
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
}
