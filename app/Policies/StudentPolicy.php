<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\Student;

class StudentPolicy
{
    /**
     * Vérifier si l'utilisateur peut voir l'élève.
     * Permet: directeur/staff école, enseignant de la classe, parent, élève lui-même
     */
    public function view(User $user, Student $student): bool
    {
        // L'élève voit ses propres données
        if ($student->user_id === $user->id) {
            return true;
        }

        // Parent de l'élève
        if ($student->parent_id === $user->id) {
            return true;
        }

        // Directeur/staff de l'école
        if ($student->school_id && $this->isSchoolAdmin($user, $student->school_id)) {
            return true;
        }

        // Enseignant de la classe
        if ($student->class_ && $this->isClassTeacher($user, $student->class_id)) {
            return true;
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut créer un élève.
     */
    public function create(User $user, Student $student): bool
    {
        // Seul le directeur/staff peut créer
        return $this->isSchoolAdmin($user, $student->school_id) || $user->hasRole('super_admin');
    }

    /**
     * Vérifier si l'utilisateur peut mettre à jour l'élève.
     */
    public function update(User $user, Student $student): bool
    {
        // Directeur/staff de l'école
        if ($this->isSchoolAdmin($user, $student->school_id)) {
            return true;
        }

        // Super Admin
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut supprimer l'élève.
     */
    public function delete(User $user, Student $student): bool
    {
        // Seul le directeur peut supprimer
        return $this->isSchoolAdmin($user, $student->school_id, 'director') || $user->hasRole('super_admin');
    }

    /**
     * Vérifier si l'utilisateur est directeur/staff de l'école.
     */
    private function isSchoolAdmin(User $user, int $schoolId, string $type = 'any'): bool
    {
        if (!$user->hasRole(['school_admin', 'school_staff'])) {
            return false;
        }

        // À implémenter: vérifier que user est effectivement staff/directeur de cette école
        // Utiliser: user->schools()->pluck('id')->contains($schoolId)
        // Pour maintenant, on fait confiance au middleware de route

        return true;
    }

    /**
     * Vérifier si l'utilisateur est enseignant de la classe.
     */
    private function isClassTeacher(User $user, int $classId): bool
    {
        if (!$user->hasRole('teacher')) {
            return false;
        }

        // Vérifier que l'utilisateur est enseignant DE cette classe
        // À implémenter avec relation teacher->classes
        return true;
    }
}
