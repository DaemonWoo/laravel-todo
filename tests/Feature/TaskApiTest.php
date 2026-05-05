<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_tasks_list(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
    }

    public function test_can_create_task(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test task',
        ]);

        $response->assertStatus(201)
        ->assertJsonPath('data.title', 'Test task');
    }

    public function test_title_is_required(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '',
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors('title');
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated');
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
    }

    
}
