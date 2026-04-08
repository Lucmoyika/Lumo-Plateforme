<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin Lumo',
                'email'    => 'admin@lumo.app',
                'password' => Hash::make('password'),
                'role'     => 'super_admin',
            ],
            [
                'name'     => 'Professeur Kouassi',
                'email'    => 'teacher@lumo.app',
                'password' => Hash::make('password'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Étudiant Diallo',
                'email'    => 'student@lumo.app',
                'password' => Hash::make('password'),
                'role'     => 'student',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(['email' => $data['email']], $data);
            $user->assignRole($role);
        }
    }
}
