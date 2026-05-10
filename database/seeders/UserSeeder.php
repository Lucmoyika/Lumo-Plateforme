<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin Lumo',
                'email'    => 'admin@lumo.app',
                'password' => 'password',
                'role'     => 'super_admin',
            ],
            [
                'name'     => 'Luc Moyika',
                'email'    => 'userlucmoyika@gmail.com',
                'password' => 'password',
                'role'     => 'super_admin',
            ],
            [
                'name'     => 'Professeur Kouassi',
                'email'    => 'teacher@lumo.app',
                'password' => 'password',
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Étudiant Diallo',
                'email'    => 'student@lumo.app',
                'password' => 'password',
                'role'     => 'student',
            ],
            [
                'name'     => 'Gestionnaire Kouame',
                'email'    => 'manager.school@lumo.app',
                'password' => 'password',
                'role'     => 'school_admin',
            ],
            [
                'name'     => 'Gestionnaire Dakar',
                'email'    => 'manager.dakar@lumo.app',
                'password' => 'password',
                'role'     => 'school_admin',
            ],
            [
                'name'     => 'Gestionnaire Lomé',
                'email'    => 'manager.lome@lumo.app',
                'password' => 'password',
                'role'     => 'school_admin',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);

            $user->name = $data['name'];
            $user->role = $data['role'];
            $user->status = 'active';

            // Let the hashed cast store a single valid hash from the plain seed password.
            $user->password = $data['password'];

            $user->save();

            $user->syncRoles([$data['role']]);
        }

        $schoolManager = User::where('email', 'manager.school@lumo.app')->first();
        $dakarManager = User::where('email', 'manager.dakar@lumo.app')->first();
        $lomeManager = User::where('email', 'manager.lome@lumo.app')->first();

        $schools = [
            'contact@excellence-abidjan.ci' => $schoolManager,
            'contact@lycee-moderne-dakar.sn' => $dakarManager,
            'secretariat@cnd-lome.tg' => $lomeManager,
        ];

        foreach ($schools as $email => $director) {
            $school = School::where('email', $email)->first();

            if ($school && $director) {
                $school->director_id = $director->id;
                $school->save();
            }
        }
    }
}
