<?php

namespace App\Modules\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['sometimes', 'string', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'avatar'  => ['nullable', 'image', 'max:2048'],
            'address' => ['nullable', 'string', 'max:255'],
            'city'    => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'bio'     => ['nullable', 'string', 'max:1000'],
            'locale'  => ['nullable', 'string', 'in:fr,en'],
            'theme'   => ['nullable', 'string', 'in:light,dark'],
        ];
    }
}
