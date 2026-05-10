<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolTasksTest extends TestCase
{
    use RefreshDatabase;

    private User $schoolAdmin;
    private School $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schoolAdmin = User::factory()->create([
            'email' => 'admin@school.test',
            'role' => 'school_admin',
        ]);

        $this->schoolAdmin->assignRole('school_admin');

        $this->school = School::factory()->create([
            'director_id' => $this->schoolAdmin->id,
            'name' => 'Test School',
        ]);
    }

    public function test_can_create_task(): void
    {
        $response = $this->actingAs($this->schoolAdmin)
            ->postJson("/api/schools/{$this->school->id}/tasks", [
                'title' => 'Prepare final exams',
                'description' => 'Plan and prepare final exams schedule',
                'priority' => 'high',
                'status' => 'todo',
                'due_date' => '2026-05-15',
                'assigned_to' => null,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Prepare final exams')
            ->assertJsonPath('data.priority', 'high');
    }

    public function test_can_list_tasks(): void
    {
        $this->school->tasks()->create([
            'title' => 'Task 1',
            'priority' => 'high',
            'status' => 'todo',
            'created_by' => $this->schoolAdmin->id,
        ]);

        $this->school->tasks()->create([
            'title' => 'Task 2',
            'priority' => 'low',
            'status' => 'in_progress',
            'created_by' => $this->schoolAdmin->id,
        ]);

        $response = $this->actingAs($this->schoolAdmin)
            ->getJson("/api/schools/{$this->school->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_update_task(): void
    {
        $task = $this->school->tasks()->create([
            'title' => 'Original Task',
            'priority' => 'low',
            'status' => 'todo',
            'created_by' => $this->schoolAdmin->id,
        ]);

        $response = $this->actingAs($this->schoolAdmin)
            ->putJson("/api/schools/{$this->school->id}/tasks/{$task->id}", [
                'status' => 'in_progress',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'in_progress');

        $this->assertDatabaseHas('school_tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_can_delete_task(): void
    {
        $task = $this->school->tasks()->create([
            'title' => 'Task to Delete',
            'priority' => 'medium',
            'status' => 'todo',
            'created_by' => $this->schoolAdmin->id,
        ]);

        $response = $this->actingAs($this->schoolAdmin)
            ->deleteJson("/api/schools/{$this->school->id}/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('school_tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_permission_checks(): void
    {
        $userWithoutPermission = User::factory()->create([
            'email' => 'user@noschool.test',
        ]);

        $response = $this->actingAs($userWithoutPermission)
            ->getJson("/api/schools/{$this->school->id}/tasks");

        $response->assertStatus(403);
    }

    public function test_task_validation(): void
    {
        $response = $this->actingAs($this->schoolAdmin)
            ->postJson("/api/schools/{$this->school->id}/tasks", [
                'title' => '',
                'priority' => 'invalid_priority',
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'priority', 'status']);
    }

    public function test_can_assign_task_to_member(): void
    {
        $teamMember = User::factory()->create([
            'email' => 'member@school.test',
        ]);

        $response = $this->actingAs($this->schoolAdmin)
            ->postJson("/api/schools/{$this->school->id}/tasks", [
                'title' => 'Assigned Task',
                'description' => 'This task is assigned',
                'priority' => 'medium',
                'status' => 'todo',
                'assigned_to' => $teamMember->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.assigned_to', $teamMember->id);
    }

    public function test_tasks_can_be_ordered(): void
    {
        $this->school->tasks()->create([
            'title' => 'Low Priority',
            'priority' => 'low',
            'status' => 'todo',
            'created_by' => $this->schoolAdmin->id,
        ]);

        $this->school->tasks()->create([
            'title' => 'High Priority',
            'priority' => 'high',
            'status' => 'todo',
            'created_by' => $this->schoolAdmin->id,
        ]);

        $response = $this->actingAs($this->schoolAdmin)
            ->getJson("/api/schools/{$this->school->id}/tasks?order_by=priority&order_dir=desc");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }
}
