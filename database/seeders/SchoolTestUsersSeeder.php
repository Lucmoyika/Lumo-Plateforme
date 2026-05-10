<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SchoolTestUsersSeeder extends Seeder
{
    /**
     * Créer une école complète avec tous les rôles pour tester.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // ─────────────────────────────────────────────────────────────
        // 1. ÉCOLE DE TEST
        // ─────────────────────────────────────────────────────────────

        $schoolDirector = User::firstOrCreate(
            ['email' => 'admin@school-test.local'],
            [
                'name' => 'M. Koffi Yao',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 04',
                'status' => 'active',
            ]
        );
        $schoolDirector->assignRole('school_admin');

        $schoolAttributes = [
            'name' => 'École Excellence Test',
            'address' => '123 Rue de l\'École',
            'city' => 'Abidjan',
            'phone' => '+225 21 22 23 24',
            'director_id' => $schoolDirector->id,
            'education_submodule' => 'full',
            'license_plan_code' => 'full_yearly',
            'subscription_status' => 'active',
            'billing_duration_months' => 12,
            'license_price_cdf' => 1290000,
            'subscription_starts_at' => now(),
            'subscription_ends_at' => now()->copy()->addMonths(12),
            'mobile_access_enabled' => true,
            'status' => 'active',
        ];

        if (Schema::hasColumn('schools', 'type')) {
            $schoolAttributes['type'] = 'primaire';
        }

        if (Schema::hasColumn('schools', 'level_types')) {
            $schoolAttributes['level_types'] = ['primaire', 'secondaire'];
        }

        $school = School::updateOrCreate(
            ['email' => 'contact@school-test.local'],
            $schoolAttributes
        );

        // ─────────────────────────────────────────────────────────────
        // 2. PERSONNEL DE L'ÉCOLE
        // ─────────────────────────────────────────────────────────────

        // Staff administratif
        $staff = User::firstOrCreate(
            ['email' => 'staff@school-test.local'],
            [
                'name' => 'Mme Atta Konan',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 05',
                'status' => 'active',
            ]
        );
        $staff->assignRole('school_staff');

        // Assistant de direction
        $assistant = User::firstOrCreate(
            ['email' => 'assistant@school-test.local'],
            [
                'name' => 'M. Kouadio Assian',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 06',
                'status' => 'active',
            ]
        );
        $assistant->assignRole('assistant');

        // ─────────────────────────────────────────────────────────────
        // 3. ENSEIGNANTS
        // ─────────────────────────────────────────────────────────────

        $teacher1 = User::firstOrCreate(
            ['email' => 'prof1@school-test.local'],
            [
                'name' => 'Mme Kouamé Berthe',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 07',
                'status' => 'active',
            ]
        );
        $teacher1->assignRole('teacher');

        Teacher::firstOrCreate(
            ['user_id' => $teacher1->id, 'school_id' => $school->id],
            [
                'employee_number' => 'T001',
                'subjects' => json_encode(['Français', 'Lecture']),
                'qualification' => 'Master Pédagogie',
                'experience_years' => 8,
                'status' => 'active',
            ]
        );

        $teacher2 = User::firstOrCreate(
            ['email' => 'prof2@school-test.local'],
            [
                'name' => 'M. Traoré Moussa',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 08',
                'status' => 'active',
            ]
        );
        $teacher2->assignRole('teacher');

        Teacher::firstOrCreate(
            ['user_id' => $teacher2->id, 'school_id' => $school->id],
            [
                'employee_number' => 'T002',
                'subjects' => json_encode(['Mathématiques', 'Calcul']),
                'qualification' => 'Licence Mathématiques',
                'experience_years' => 5,
                'status' => 'active',
            ]
        );

        // Enseignant remplaçant
        $subTeacher = User::firstOrCreate(
            ['email' => 'sub-prof@school-test.local'],
            [
                'name' => 'M. Diallo Amadou',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 09',
                'status' => 'active',
            ]
        );
        $subTeacher->assignRole('substitute_teacher');

        // ─────────────────────────────────────────────────────────────
        // 4. CLASSES
        // ─────────────────────────────────────────────────────────────

        $class1 = SchoolClass::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'CP1'],
            [
                'level' => 'CP1',
                'academic_year' => '2024-2025',
                'teacher_id' => $teacher1->id,
                'max_students' => 40,
                'room' => 'A-101',
                'status' => 'active',
            ]
        );

        $class2 = SchoolClass::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'CP2'],
            [
                'level' => 'CP2',
                'academic_year' => '2024-2025',
                'teacher_id' => $teacher2->id,
                'max_students' => 40,
                'room' => 'A-102',
                'status' => 'active',
            ]
        );

        $class3 = SchoolClass::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'CE1'],
            [
                'level' => 'CE1',
                'academic_year' => '2024-2025',
                'teacher_id' => $teacher1->id,
                'max_students' => 35,
                'room' => 'B-201',
                'status' => 'active',
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // 5. ÉLÈVES ET PARENTS
        // ─────────────────────────────────────────────────────────────

        // Parent 1
        $parent1 = User::firstOrCreate(
            ['email' => 'parent1@school-test.local'],
            [
                'name' => 'M. Yao Sief',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 10',
                'status' => 'active',
            ]
        );
        $parent1->assignRole('parent');

        // Élève 1 (CP1)
        $student1 = User::firstOrCreate(
            ['email' => 'student1@school-test.local'],
            [
                'name' => 'Kaïs Yao',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 11',
                'status' => 'active',
            ]
        );
        $student1->assignRole('student');

        Student::firstOrCreate(
            ['user_id' => $student1->id, 'school_id' => $school->id],
            [
                'class_id' => $class1->id,
                'student_number' => 'STU001',
                'enrollment_date' => now(),
                'parent_id' => $parent1->id,
                'status' => 'active',
            ]
        );

        // Parent 2 (parent de 2 enfants)
        $parent2 = User::firstOrCreate(
            ['email' => 'parent2@school-test.local'],
            [
                'name' => 'Mme Kouakou Marie',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 12',
                'status' => 'active',
            ]
        );
        $parent2->assignRole('parent');

        // Élève 2 (CP1)
        $student2 = User::firstOrCreate(
            ['email' => 'student2@school-test.local'],
            [
                'name' => 'Aïcha Kouakou',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 13',
                'status' => 'active',
            ]
        );
        $student2->assignRole('student');

        Student::firstOrCreate(
            ['user_id' => $student2->id, 'school_id' => $school->id],
            [
                'class_id' => $class1->id,
                'student_number' => 'STU002',
                'enrollment_date' => now(),
                'parent_id' => $parent2->id,
                'status' => 'active',
            ]
        );

        // Élève 3 (CP2 - frère/sœur de student2)
        $student3 = User::firstOrCreate(
            ['email' => 'student3@school-test.local'],
            [
                'name' => 'Kwami Kouakou',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 14',
                'status' => 'active',
            ]
        );
        $student3->assignRole('student');

        Student::firstOrCreate(
            ['user_id' => $student3->id, 'school_id' => $school->id],
            [
                'class_id' => $class2->id,
                'student_number' => 'STU003',
                'enrollment_date' => now(),
                'parent_id' => $parent2->id,
                'status' => 'active',
            ]
        );

        // Élève 4 (CE1)
        $student4 = User::firstOrCreate(
            ['email' => 'student4@school-test.local'],
            [
                'name' => 'Fatou Diallo',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 15',
                'status' => 'active',
            ]
        );
        $student4->assignRole('student');

        Student::firstOrCreate(
            ['user_id' => $student4->id, 'school_id' => $school->id],
            [
                'class_id' => $class3->id,
                'student_number' => 'STU004',
                'enrollment_date' => now(),
                'parent_id' => $parent1->id,
                'status' => 'active',
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // 6. SUPER ADMIN (N'a pas d'accès automatique)
        // ─────────────────────────────────────────────────────────────

        $superAdmin = User::firstOrCreate(
            ['email' => 'super@app.test'],
            [
                'name' => 'Admin Système',
                'password' => Hash::make('password'),
                'phone' => '+225 01 02 03 99',
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole('super_admin');

        echo "✅ Seeder SchoolTestUsers complété!\n\n";
        echo "📋 Utilisateurs créés:\n";
        echo "═══════════════════════════════════════════════════════════════\n";
        echo "ÉCOLE: École Excellence Test (ID: {$school->id})\n";
        echo "───────────────────────────────────────────────────────────────\n";
        echo "DIRECTEUR:\n";
        echo "  • admin@school-test.local / password\n";
        echo "\nSTAFF:\n";
        echo "  • staff@school-test.local / password\n";
        echo "  • assistant@school-test.local / password\n";
        echo "\nENSEIGNANTS:\n";
        echo "  • prof1@school-test.local / password (CP1 + CE1)\n";
        echo "  • prof2@school-test.local / password (CP2)\n";
        echo "  • sub-prof@school-test.local / password (remplaçant)\n";
        echo "\nPARENTS:\n";
        echo "  • parent1@school-test.local / password (student1, student4)\n";
        echo "  • parent2@school-test.local / password (student2, student3)\n";
        echo "\nÉLÈVES:\n";
        echo "  • student1@school-test.local / password (CP1, parent1)\n";
        echo "  • student2@school-test.local / password (CP1, parent2)\n";
        echo "  • student3@school-test.local / password (CP2, parent2)\n";
        echo "  • student4@school-test.local / password (CE1, parent1)\n";
        echo "\nSUPER ADMIN:\n";
        echo "  • super@app.test / password (N'a PAS d'accès à l'école)\n";
        echo "═══════════════════════════════════════════════════════════════\n";
    }
}
