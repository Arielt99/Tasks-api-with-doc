<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test with all fields correctly filled and valid token given.
     *
     * @return void
     */
    public function test_delete_task_all_fields_correct()
    {
        $credential = [];

        $user = User::factory()->create();

        $task = Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => $user->id]);

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = $this->json('DELETE', '/api/tasks/'.$task->id, $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token]);

        $response
        ->assertStatus(200)
        ->assertJsonStructure(['message'])
        ->assertJsonPath('message', "Task deleted successfully.");
    }


    //id
    /**
     * Test with an id non corresponding to one of current user tasks.
     *
     * @return void
     */
    public function test_delete_task_id_not_matching_current_user_tasks()
    {
        $credential = [];

        $task = Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => User::factory()->create()->id]);

        $token = User::factory()->create()->createToken('my-app-token')->plainTextToken;

        $response = $this->json('DELETE', '/api/tasks/'.$task->id, $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token]);

        $response
        ->assertStatus(404);
    }


    //token
    /**
     * Test with token not given or expired while updating one task.
     *
     * @return void
     */
    public function test_delete_task_token_not_given_or_expired()
    {
        $credential = [];

        $user = User::factory()->create();

        $task = Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => $user->id]);

        $token = $user->createToken('my-app-token')->plainTextToken;

        $token = '';

        $response = $this->json('DELETE', '/api/tasks/'.$task->id, $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token]);

        $response
        ->assertStatus(401)
        ->assertJsonStructure(['message'])
        ->assertJsonPath('message', "Unauthenticated.");
    }
}
