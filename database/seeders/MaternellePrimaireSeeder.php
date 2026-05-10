<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Modules\Education\Ecoles\Models\Teacher;
use App\Modules\Education\Ecoles\Models\Student;
use Illuminate\Database\Seeder;

class MaternellePrimaireSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour créer les écoles Maternelle et Primaire avec leurs données
     */
    public function run(): void
    {
        // ========================================
        // 1. CRÉER L'ÉCOLE MATERNELLE
        // ========================================
        $maternyelle = School::firstOrCreate(
            ['email' => 'directrice.maternelle@lumo.app'],
            [
                'name' => 'École Maternelle Les Bambins',
                'address' => 'Avenue Momemtain, Zone Verte',
                'city' => 'Kinshasa',
                'province' => 'Kinshasa',
                'phone' => '+243 81 000 0001',
                'email' => 'directrice.maternelle@lumo.app',
                'website' => 'https://maternelle-bambins.cd',
                'level_types' => ['maternelle'],
                'education_submodule' => 'mp',
                'status' => 'active',
                'subscription_status' => 'active',
                'license_plan_code' => 'mp_yearly',
                'billing_duration_months' => 12,
                'license_price_cdf' => 680000,
                'mobile_access_enabled' => true,
            ]
        );

        // Assigner le directeur à la Maternelle
        $directorMat = User::firstOrCreate(
            ['email' => 'directrice.maternelle@lumo.app'],
            [
                'name' => 'Mme. Félicité Kilo',
                'email' => 'directrice.maternelle@lumo.app',
                'phone' => '+243 81 000 0001',
                'password' => bcrypt('password'),
            ]
        );
        $maternyelle->director_id = $directorMat->id;
        $maternyelle->save();

        // ========================================
        // 2. CRÉER LES ENSEIGNANTES MATERNELLE (Femmes uniquement)
        // ========================================
        $teachersMat = [
            ['name' => 'Mme. Antoinette Makoso', 'email' => 'antoinette.makoso@lumo.app', 'level' => '1er', 'variant' => null],
            ['name' => 'Mme. Grace Mvembi', 'email' => 'grace.mvembi@lumo.app', 'level' => '2e', 'variant' => null],
            ['name' => 'Mme. Jeanne Kabila', 'email' => 'jeanne.kabila@lumo.app', 'level' => '3e', 'variant' => null],
            ['name' => 'Mme. Marie Kalombo', 'email' => 'marie.kalombo@lumo.app', 'role' => 'assistant'],
        ];

        $teacherMatIds = [];
        foreach ($teachersMat as $idx => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => '+243 81 100 000' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                    'password' => bcrypt('password'),
                ]
            );

            $role = $data['role'] ?? 'teacher';
            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id, 'school_id' => $maternyelle->id],
                [
                    'employee_number' => 'MAT-TSR-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                    'qualification' => 'Bac + 2 (Maternelle)',
                    'experience_years' => rand(2, 15),
                    'gender' => 'F', // Maternelle: femmes uniquement
                    'contract_type' => 'annual',
                    'role' => $role,
                    'status' => 'active',
                    'subjects' => ['Pédagogie', 'Jeux', 'Art'],
                ]
            );

            $teacherMatIds[$role === 'assistant' ? 'assistant' : $data['level']] = $teacher->id;
        }

        // ========================================
        // 3. CRÉER LES CLASSES MATERNELLE
        // ========================================
        $classesMat = [
            ['level' => '1er', 'name' => 'Classe 1er - Petite Section'],
            ['level' => '2e', 'name' => 'Classe 2e - Moyenne Section'],
            ['level' => '3e', 'name' => 'Classe 3e - Grande Section'],
        ];

        foreach ($classesMat as $class) {
            SchoolClass::firstOrCreate(
                ['school_id' => $maternyelle->id, 'level' => $class['level'], 'academic_year' => '2025-2026'],
                [
                    'name' => $class['name'],
                    'level' => $class['level'],
                    'class_variant' => null,
                    'education_submodule' => 'mp',
                    'academic_year' => '2025-2026',
                    'teacher_id' => $teacherMatIds[$class['level']] ?? null,
                    'max_students' => 18,
                    'room' => 'Salle ' . $class['level'],
                    'status' => 'active',
                ]
            );
        }

        echo "✓ Maternelle créée avec 3 classes et 4 enseignantes\n";

        // ========================================
        // 4. CRÉER L'ÉCOLE PRIMAIRE
        // ========================================
        $primaire = School::firstOrCreate(
            ['email' => 'directeur.primaire@lumo.app'],
            [
                'name' => 'École Primaire Horizon Nouveau',
                'address' => 'Boulevard de la Révolution, Gombe',
                'city' => 'Kinshasa',
                'province' => 'Kinshasa',
                'phone' => '+243 81 000 0002',
                'email' => 'directeur.primaire@lumo.app',
                'website' => 'https://primaire-horizon.cd',
                'level_types' => ['primaire'],
                'education_submodule' => 'mp',
                'status' => 'active',
                'subscription_status' => 'active',
                'license_plan_code' => 'mp_yearly',
                'billing_duration_months' => 12,
                'license_price_cdf' => 680000,
                'mobile_access_enabled' => true,
            ]
        );

        // Assigner le directeur au Primaire
        $directorPrim = User::firstOrCreate(
            ['email' => 'directeur.primaire@lumo.app'],
            [
                'name' => 'M. Jean-Pierre Bolamba',
                'email' => 'directeur.primaire@lumo.app',
                'phone' => '+243 81 000 0002',
                'password' => bcrypt('password'),
            ]
        );
        $primaire->director_id = $directorPrim->id;
        $primaire->save();

        // ========================================
        // 5. CRÉER LES ENSEIGNANTS PRIMAIRE (Hommes et Femmes)
        // ========================================
        $teachersPrim = [
            // 1er - Deux variantes (A et B)
            ['name' => 'M. François Ekunda', 'email' => 'francois.ekunda@lumo.app', 'level' => '1er', 'variant' => 'A', 'gender' => 'M'],
            ['name' => 'Mme. Sophie Musangu', 'email' => 'sophie.musangu@lumo.app', 'level' => '1er', 'variant' => 'B', 'gender' => 'F'],
            // 2e - Deux variantes
            ['name' => 'M. André Nkombo', 'email' => 'andre.nkombo@lumo.app', 'level' => '2e', 'variant' => 'A', 'gender' => 'M'],
            ['name' => 'Mme. Claire Sanda', 'email' => 'claire.sanda@lumo.app', 'level' => '2e', 'variant' => 'B', 'gender' => 'F'],
            // 3e à 6e - Une variante chacun
            ['name' => 'M. Paul Lamba', 'email' => 'paul.lamba@lumo.app', 'level' => '3e', 'variant' => 'A', 'gender' => 'M'],
            ['name' => 'M. Théo Mbuyi', 'email' => 'theo.mbuyi@lumo.app', 'level' => '4e', 'variant' => 'A', 'gender' => 'M'],
            ['name' => 'Mme. Ruth Kabwe', 'email' => 'ruth.kabwe@lumo.app', 'level' => '5e', 'variant' => 'A', 'gender' => 'F'],
            ['name' => 'M. David Kasanda', 'email' => 'david.kasanda@lumo.app', 'level' => '6e', 'variant' => 'A', 'gender' => 'M'],
            // Assistants (mixte)
            ['name' => 'Mme. Nicole Tsongo', 'email' => 'nicole.tsongo@lumo.app', 'role' => 'assistant', 'gender' => 'F'],
            ['name' => 'M. Roger Muamba', 'email' => 'roger.muamba@lumo.app', 'role' => 'assistant', 'gender' => 'M'],
        ];

        $teacherPrimIds = [];
        foreach ($teachersPrim as $idx => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => '+243 81 200 000' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                    'password' => bcrypt('password'),
                ]
            );

            $role = $data['role'] ?? 'teacher';
            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id, 'school_id' => $primaire->id],
                [
                    'employee_number' => 'PRI-TSR-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                    'qualification' => 'Bac + 3 (Pédagogie)',
                    'experience_years' => rand(1, 20),
                    'gender' => $data['gender'],
                    'contract_type' => 'annual',
                    'role' => $role,
                    'status' => 'active',
                    'subjects' => ['Français', 'Mathématiques', 'Sciences', 'Géographie', 'Histoire'],
                ]
            );

            if ($role === 'assistant') {
                $teacherPrimIds['assistant_' . count(array_filter($teacherPrimIds, fn($k) => strpos($k, 'assistant_') === 0))] = $teacher->id;
            } else {
                $key = $data['level'] . '_' . ($data['variant'] ?? 'A');
                $teacherPrimIds[$key] = $teacher->id;
            }
        }

        // ========================================
        // 6. CRÉER LES CLASSES PRIMAIRE
        // ========================================
        $classesPrim = [];
        $levels = ['1er', '2e', '3e', '4e', '5e', '6e'];
        
        foreach ($levels as $level) {
            if ($level === '1er' || $level === '2e') {
                // 1er et 2e ont 2 variantes (A et B)
                $classesPrim[] = ['level' => $level, 'variant' => 'A', 'name' => "{$level} A"];
                $classesPrim[] = ['level' => $level, 'variant' => 'B', 'name' => "{$level} B"];
            } else {
                // 3e à 6e n'ont qu'une variante (A)
                $classesPrim[] = ['level' => $level, 'variant' => 'A', 'name' => "{$level} A"];
            }
        }

        foreach ($classesPrim as $class) {
            $key = $class['level'] . '_' . $class['variant'];
            SchoolClass::firstOrCreate(
                ['school_id' => $primaire->id, 'level' => $class['level'], 'class_variant' => $class['variant'], 'academic_year' => '2025-2026'],
                [
                    'name' => $class['name'],
                    'level' => $class['level'],
                    'class_variant' => $class['variant'],
                    'education_submodule' => 'mp',
                    'academic_year' => '2025-2026',
                    'teacher_id' => $teacherPrimIds[$key] ?? null,
                    'max_students' => 28,
                    'room' => 'Salle ' . $class['name'],
                    'status' => 'active',
                ]
            );
        }

        echo "✓ Primaire créée avec 8 classes et 10 enseignants\n";

        // ========================================
        // 7. AJOUTER DES ÉLÈVES DE TEST
        // ========================================
        $studentNames = ['Emile', 'Marie', 'Jean', 'Anne', 'Pierre', 'Sophie', 'Luc', 'Jeanne'];
        
        // Élèves Maternelle
        $materClasses = SchoolClass::where('school_id', $maternyelle->id)->get();
        foreach ($materClasses as $class) {
            for ($i = 1; $i <= 4; $i++) {
                $name = $studentNames[($i - 1) % count($studentNames)];
                $email = 'etudiant.mat.' . $class->id . '.' . $i . '@lumo.app';
                
                $studentUser = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => "$name Maternelle {$class->level} " . $i,
                        'email' => $email,
                        'phone' => '+243 81 300 000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'password' => bcrypt('password'),
                    ]
                );
                
                Student::firstOrCreate(
                    ['user_id' => $studentUser->id, 'class_id' => $class->id],
                    [
                        'school_id' => $maternyelle->id,
                        'class_id' => $class->id,
                        'student_number' => 'MAT-' . str_pad($class->id, 3, '0', STR_PAD_LEFT) . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'status' => 'active',
                    ]
                );
            }
        }

        // Élèves Primaire
        $primClasses = SchoolClass::where('school_id', $primaire->id)->get();
        foreach ($primClasses as $class) {
            for ($i = 1; $i <= 6; $i++) {
                $name = $studentNames[($i - 1) % count($studentNames)];
                $email = 'etudiant.prim.' . $class->id . '.' . $i . '@lumo.app';
                
                $studentUser = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => "$name Primaire {$class->full_name} " . $i,
                        'email' => $email,
                        'phone' => '+243 81 400 000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'password' => bcrypt('password'),
                    ]
                );
                
                Student::firstOrCreate(
                    ['user_id' => $studentUser->id, 'class_id' => $class->id],
                    [
                        'school_id' => $primaire->id,
                        'class_id' => $class->id,
                        'student_number' => 'PRI-' . str_pad($class->id, 3, '0', STR_PAD_LEFT) . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'status' => 'active',
                    ]
                );
            }
        }

        echo "✓ Élèves créés pour Maternelle et Primaire\n";
        echo "\n========== DONNÉES DE TEST CRÉÉES ==========\n";
        echo "Maternelle:\n";
        echo "  - Directrice: directrice.maternelle@lumo.app / password\n";
        echo "  - 3 niveaux (1er, 2e, 3e) avec enseignantes (femmes uniquement)\n";
        echo "  - 4 enseignantes + 1 assistante\n";
        echo "\nPrimaire:\n";
        echo "  - Directeur: directeur.primaire@lumo.app / password\n";
        echo "  - 6 niveaux (1er-6e) avec variantes A, B pour 1er-2e\n";
        echo "  - 8 enseignants (hommes et femmes) + 2 assistants\n";
        echo "=========================================\n";
    }
}
