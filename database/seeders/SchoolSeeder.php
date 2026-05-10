<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name'        => 'École Primaire Excellence Abidjan',
                'address'     => 'Quartier Cocody, Rue des Jardins',
                'city'        => 'Abidjan',
                'phone'       => '+225 27 22 40 00 00',
                'email'       => 'contact@excellence-abidjan.ci',
                'level_types' => ['primaire'],
                'status'      => 'active',
            ],
            [
                'name'        => 'Lycée Moderne de Dakar',
                'address'     => 'Avenue Cheikh Anta Diop, Plateau',
                'city'        => 'Dakar',
                'phone'       => '+221 33 821 00 00',
                'email'       => 'contact@lycee-moderne-dakar.sn',
                'level_types' => ['secondaire', 'humanites'],
                'status'      => 'active',
            ],
            [
                'name'        => 'Collège Notre-Dame de Lomé',
                'address'     => 'Boulevard du 13 Janvier, Tokoin',
                'city'        => 'Lomé',
                'phone'       => '+228 22 21 31 65',
                'email'       => 'secretariat@cnd-lome.tg',
                'level_types' => ['primaire', 'secondaire', 'humanites'],
                'status'      => 'active',
            ],
        ];

        foreach ($schools as $school) {
            School::firstOrCreate(['email' => $school['email']], $school);
        }

        $directors = [
            'contact@excellence-abidjan.ci' => 'manager.school@lumo.app',
            'contact@lycee-moderne-dakar.sn' => 'manager.dakar@lumo.app',
            'secretariat@cnd-lome.tg' => 'manager.lome@lumo.app',
        ];

        foreach ($directors as $schoolEmail => $directorEmail) {
            $school = School::where('email', $schoolEmail)->first();
            $director = User::where('email', $directorEmail)->first();

            if ($school && $director) {
                $school->director_id = $director->id;
                $school->save();
            }
        }
    }
}
