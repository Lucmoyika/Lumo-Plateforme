<?php

namespace App\Modules\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['nullable', 'string', 'in:student,teacher,parent,admin,company,director'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => "L'adresse e-mail est obligatoire.",
            'email.unique'       => 'Cette adresse e-mail est déjà utilisée.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'role.in'            => 'Le rôle sélectionné est invalide.',
        ];
    }
}
