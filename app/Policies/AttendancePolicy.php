<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\Attendance;

class AttendancePolicy
{
    /**
     * Vérifier si l'utilisateur peut voir la présence.
     * Permet: directeur/staff école, enseignant de la classe, parent de l'élève, élève lui-même
     */
    public function view(User $user, Attendance $attendance): bool
    {
        // L'élève voit ses propres présences
        if ($attendance->student_id && $this->isStudentSelf($user, $attendance->student_id)) {
            return true;
        }

        // Parent de l'élève
        if ($attendance->student_id && $this->isParentOf($user, $attendance->student_id)) {
            return true;
        }

        // Enseignant de la classe
        if ($attendance->class_id && $this->isClassTeacher($user, $attendance->class_id)) {
            return true;
        }

        // Directeur/staff de l'école
        if ($attendance->class_id) {
            $schoolId = $this->getClassSchoolId($attendance->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut créer une présence.
     */
    public function create(User $user, Attendance $attendance): bool
    {
        // Enseignant de la classe
        if ($attendance->class_id && $this->isClassTeacher($user, $attendance->class_id)) {
            return true;
        }

        // Directeur/staff
        if ($attendance->class_id) {
            $schoolId = $this->getClassSchoolId($attendance->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut mettre à jour la présence.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        // Enseignant de la classe
        if ($attendance->class_id && $this->isClassTeacher($user, $attendance->class_id)) {
            return true;
        }

        // Directeur/staff
        if ($attendance->class_id) {
            $schoolId = $this->getClassSchoolId($attendance->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut supprimer la présence.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        // Seul le directeur peut supprimer
        if ($attendance->class_id) {
            $schoolId = $this->getClassSchoolId($attendance->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId, 'director')) {
                return true;
            }
        }

        return $user->hasRole('super_admin');
    }

    // ─────────────────────────────────────────────────────────────

    private function isStudentSelf(User $user, int $studentId): bool
    {
        // Vérifier si l'utilisateur EST l'élève
        // À implémenter: student.user_id === user.id
        return false; // Placeholder
    }

    private function isParentOf(User $user, int $studentId): bool
    {
        // Vérifier si l'utilisateur est parent de l'élève
        // À implémenter: student.parent_id === user.id
        return false; // Placeholder
    }

    private function isClassTeacher(User $user, int $classId): bool
    {
        // Vérifier si l'utilisateur est enseignant DE cette classe
        return false; // Placeholder
    }

    private function isSchoolAdmin(User $user, int $schoolId, string $type = 'any'): bool
    {
        if (!$user->hasRole(['school_admin', 'school_staff'])) {
            return false;
        }

        return true;
    }

    private function getClassSchoolId(int $classId): ?int
    {
        // À implémenter: récupérer school_id via class.school_id
        return null; // Placeholder
    }
}
