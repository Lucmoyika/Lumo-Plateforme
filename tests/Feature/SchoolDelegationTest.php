<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\Teacher;
use App\Modules\Education\Ecoles\Models\SchoolPermissionDelegation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SchoolDelegationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['schools.view', 'schools.delegate', 'teachers.view', 'teachers.create', 'students.view', 'grades.view', 'grades.create', 'grades.update', 'attendance.view', 'attendance.create'] as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $role = Role::firstOrCreate(['name' => 'school_admin']);
        $role->syncPermissions(['schools.view', 'schools.delegate', 'teachers.view', 'teachers.create', 'students.view', 'grades.view', 'grades.create', 'grades.update', 'attendance.view', 'attendance.create']);
    }

    public function test_school_director_can_create_temporary_delegation(): void
    {
        $director = User::factory()->create(['role' => 'school_admin', 'status' => 'active']);
        $director->assignRole('school_admin');
        $director->givePermissionTo(['schools.delegate', 'grades.view']);

        $school = School::query()->create([
            'name' => 'École Test',
            'type' => 'primaire',
            'city' => 'Kinshasa',
            'status' => 'active',
            'director_id' => $director->id,
        ]);

        $member = User::factory()->create(['status' => 'active']);
        Teacher::query()->create([
            'user_id' => $member->id,
            'school_id' => $school->id,
            'employee_number' => 'T-001',
            'subjects' => ['Maths'],
            'status' => 'active',
        ]);

        Sanctum::actingAs($director, ['*']);

        $response = $this->postJson("/api/schools/{$school->id}/members/{$member->id}/delegations", [
            'role_name' => 'Responsable notes',
            'permissions' => ['grades.view'],
            'starts_at' => now()->subHour()->toDateTimeString(),
            'ends_at' => now()->addDay()->toDateTimeString(),
            'notes' => 'Appui temporaire',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('school_permission_delegations', [
            'school_id' => $school->id,
            'user_id' => $member->id,
            'role_name' => 'Responsable notes',
        ]);
    }

    public function test_school_director_can_revoke_temporary_delegation(): void
    {
        $director = User::factory()->create(['role' => 'school_admin', 'status' => 'active']);
        $director->assignRole('school_admin');
        $director->givePermissionTo(['schools.delegate', 'grades.view']);

        $school = School::query()->create([
            'name' => 'École Test',
            'type' => 'primaire',
            'city' => 'Kinshasa',
            'status' => 'active',
            'director_id' => $director->id,
        ]);
        $member = User::factory()->create(['status' => 'active']);
        Teacher::query()->create([
            'user_id' => $member->id,
            'school_id' => $school->id,
            'employee_number' => 'T-001',
            'subjects' => ['Maths'],
            'status' => 'active',
        ]);

        $delegation = SchoolPermissionDelegation::query()->create([
            'school_id' => $school->id,
            'user_id' => $member->id,
            'granted_by' => $director->id,
            'role_name' => 'Responsable notes',
            'permissions' => ['grades.view'],
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDay(),
        ]);

        Sanctum::actingAs($director, ['*']);

        $response = $this->deleteJson("/api/schools/{$school->id}/members/{$member->id}/delegations/{$delegation->id}");

        $response->assertOk();

        $delegation->refresh();
        $this->assertNotNull($delegation->revoked_at);
    }

    public function test_user_outside_school_is_denied_access_to_school_scope(): void
    {
        $director = User::factory()->create(['role' => 'school_admin', 'status' => 'active']);
        $director->assignRole('school_admin');
        $director->givePermissionTo(['schools.delegate']);

        $school = School::query()->create([
            'name' => 'École Test',
            'type' => 'primaire',
            'city' => 'Kinshasa',
            'status' => 'active',
            'director_id' => $director->id,
        ]);

        $outsider = User::factory()->create(['status' => 'active']);
        $outsider->assignRole('school_admin');
        $outsider->givePermissionTo(['schools.delegate']);

        Sanctum::actingAs($outsider, ['*']);

        $response = $this->getJson("/api/schools/{$school->id}/members");

        $response->assertStatus(403);
    }
}