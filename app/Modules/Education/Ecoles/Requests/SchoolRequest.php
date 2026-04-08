<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $schoolId = $this->route('school');

        return [
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string', 'in:primary,secondary,high,technical,private'],
            'address'     => ['required', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:100'],
            'country'     => ['nullable', 'string', 'max:100'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255'],
            'website'     => ['nullable', 'url', 'max:255'],
            'director_id' => ['nullable', 'exists:users,id'],
            'status'      => ['nullable', 'string', 'in:active,inactive,pending'],
            'description' => ['nullable', 'string'],
        ];
    }
}
