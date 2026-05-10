<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;

class SchoolPolicy
{
    /**
     * Vérifier si l'utilisateur peut voir l'école.
     * Permet: directeur, staff, super_admin qui a rôle school_admin
     */
    public function view(User $user, School $school): bool
    {
        // Super Admin avec le rôle school_admin
        if ($user->hasRole('super_admin') && $user->hasRole('school_admin')) {
            return true;
        }

        // Directeur de l'école
        if ($school->director_id === $user->id) {
            return true;
        }

        // Staff de l'école (pas encore implémenté, mais préparé)
        // À implémenter: table school_staff avec relation

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut créer une école.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    /**
     * Vérifier si l'utilisateur peut mettre à jour l'école.
     */
    public function update(User $user, School $school): bool
    {
        // Directeur peut mettre à jour sa propre école
        if ($school->director_id === $user->id) {
            return true;
        }

        // Super Admin ou Admin
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut supprimer l'école.
     */
    public function delete(User $user, School $school): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    /**
     * Vérifier si l'utilisateur peut déléguer des permissions.
     */
    public function delegate(User $user, School $school): bool
    {
        // Seul le directeur peut déléguer
        return $school->director_id === $user->id || $user->hasRole('super_admin');
    }
}
