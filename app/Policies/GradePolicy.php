<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\Grade;

class GradePolicy
{
    /**
     * Vérifier si l'utilisateur peut voir la note.
     * Permet: directeur/staff école, enseignant de la classe, parent de l'élève, élève lui-même
     */
    public function view(User $user, Grade $grade): bool
    {
        // L'élève voit ses propres notes
        if ($grade->student_id && $this->isStudentSelf($user, $grade->student_id)) {
            return true;
        }

        // Parent de l'élève
        if ($grade->student_id && $this->isParentOf($user, $grade->student_id)) {
            return true;
        }

        // Enseignant de la classe
        if ($grade->class_id && $this->isClassTeacher($user, $grade->class_id)) {
            return true;
        }

        // Directeur/staff de l'école
        if ($grade->class_id) {
            $schoolId = $this->getClassSchoolId($grade->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut créer une note.
     */
    public function create(User $user, Grade $grade): bool
    {
        // Enseignant de la classe
        if ($grade->class_id && $this->isClassTeacher($user, $grade->class_id)) {
            return true;
        }

        // Directeur/staff
        if ($grade->class_id) {
            $schoolId = $this->getClassSchoolId($grade->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut mettre à jour la note.
     */
    public function update(User $user, Grade $grade): bool
    {
        // Enseignant de la classe (peut mettre à jour ses notes)
        if ($grade->class_id && $this->isClassTeacher($user, $grade->class_id)) {
            return true;
        }

        // Directeur/staff
        if ($grade->class_id) {
            $schoolId = $this->getClassSchoolId($grade->class_id);
            if ($schoolId && $this->isSchoolAdmin($user, $schoolId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut supprimer la note.
     */
    public function delete(User $user, Grade $grade): bool
    {
        // Seul le directeur peut supprimer
        if ($grade->class_id) {
            $schoolId = $this->getClassSchoolId($grade->class_id);
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
        // À implémenter avec relation teacher->classes
        return false; // Placeholder
    }

    private function isSchoolAdmin(User $user, int $schoolId, string $type = 'any'): bool
    {
        if (!$user->hasRole(['school_admin', 'school_staff'])) {
            return false;
        }

        // À implémenter: vérifier que user est effectivement staff/directeur de cette école
        return true;
    }

    private function getClassSchoolId(int $classId): ?int
    {
        // À implémenter: récupérer school_id via class.school_id
        return null; // Placeholder
    }
}
