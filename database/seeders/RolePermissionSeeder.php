<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Schools
            'schools.view', 'schools.create', 'schools.update', 'schools.delete',
            'school-classes.view', 'school-classes.create', 'school-classes.update', 'school-classes.delete',
            'students.view', 'students.create', 'students.update', 'students.delete',
            'teachers.view', 'teachers.create', 'teachers.update', 'teachers.delete',
            'attendance.view', 'attendance.create', 'attendance.update',
            'grades.view', 'grades.create', 'grades.update', 'grades.delete',
            // Universities
            'universities.view', 'universities.create', 'universities.update', 'universities.delete',
            'faculties.view', 'faculties.create', 'faculties.update', 'faculties.delete',
            'departments.view', 'departments.create', 'departments.update', 'departments.delete',
            'courses.view', 'courses.create', 'courses.update', 'courses.delete',
            'theses.view', 'theses.create', 'theses.update', 'theses.delete',
            // Emploi
            'jobs.view', 'jobs.create', 'jobs.update', 'jobs.delete',
            'job-applications.view', 'job-applications.create', 'job-applications.update', 'job-applications.delete',
            // Entreprises
            'companies.view', 'companies.create', 'companies.update', 'companies.delete',
            // Ecommerce
            'products.view', 'products.create', 'products.update', 'products.delete',
            'orders.view', 'orders.create', 'orders.update', 'orders.delete',
            'product-categories.view', 'product-categories.create', 'product-categories.update', 'product-categories.delete',
            // Paiement
            'payments.view', 'payments.create',
            'wallet.view', 'wallet.deposit', 'wallet.withdraw', 'wallet.transfer',
            // Logistique
            'shipments.view', 'shipments.create', 'shipments.update', 'shipments.delete',
            // Communication
            'conversations.view', 'conversations.create', 'conversations.delete',
            'messages.view', 'messages.create', 'messages.delete',
            // Core
            'videos.view', 'videos.create', 'videos.update', 'videos.delete',
            'settings.view', 'settings.update',
            'analytics.view',
            'audit-logs.view',
            'users.view', 'users.create', 'users.update', 'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'super_admin'      => array_keys(array_flip($permissions)),
            'admin'            => array_diff($permissions, ['users.delete']),
            'school_admin'     => ['schools.view', 'schools.create', 'schools.update', 'school-classes.view', 'school-classes.create', 'school-classes.update', 'students.view', 'students.create', 'students.update', 'teachers.view', 'teachers.create', 'teachers.update', 'attendance.view', 'attendance.create', 'grades.view', 'grades.create', 'grades.update'],
            'university_admin' => ['universities.view', 'universities.create', 'universities.update', 'faculties.view', 'faculties.create', 'departments.view', 'departments.create', 'courses.view', 'courses.create', 'theses.view', 'theses.create'],
            'company_admin'    => ['companies.view', 'companies.create', 'companies.update', 'jobs.view', 'jobs.create', 'jobs.update', 'job-applications.view'],
            'teacher'          => ['schools.view', 'students.view', 'attendance.view', 'attendance.create', 'grades.view', 'grades.create', 'grades.update', 'videos.view'],
            'student'          => ['schools.view', 'grades.view', 'attendance.view', 'videos.view', 'jobs.view', 'products.view', 'conversations.view', 'conversations.create', 'messages.view', 'messages.create'],
            'customer'         => ['products.view', 'orders.view', 'orders.create', 'payments.view', 'payments.create', 'wallet.view', 'wallet.deposit', 'wallet.withdraw', 'wallet.transfer', 'conversations.view', 'conversations.create', 'messages.view', 'messages.create'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
