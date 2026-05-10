<?php

namespace App\Modules\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        $password = $this->input('password');

        $this->merge([
            'email' => is_string($email) ? mb_strtolower(trim($email)) : $email,
            'password' => is_string($password) ? trim($password) : $password,
        ]);
    }

    public function messages(): array
    {
        return [
            'email.required'    => "L'adresse e-mail est obligatoire.",
            'email.email'       => "L'adresse e-mail doit être valide.",
            'password.required' => 'Le mot de passe est obligatoire.',
        ];
    }
}
