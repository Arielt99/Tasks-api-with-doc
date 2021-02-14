<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskReadTest extends TestCase
{
    use RefreshDatabase;
    ///one user's task
    /**
     * Test with all fields correctly filled and valid token given.
     *
     * @return void
     */
    public function test_read_one_task_all_fields_correct()
    {
        $credential = [];

        $user = User::factory()->create();

        $task = Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => $user->id]);

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = $this->json('GET', '/api/tasks/'.$task->id,$credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(200)
        ->assertJsonStructure(['task'])
        ->assertJsonPath('task.id', $task->id);;
    }

    //id
    /**
     * Test with an id non corresponding to one of current user tasks.
     *
     * @return void
     */
    public function test_read_one_task_id_not_matching_current_user_tasks()
    {
        $credential = [];

        $task = Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => User::factory()->create()->id]);

        $token = User::factory()->create()->createToken('my-app-token')->plainTextToken;

        $response = $this->json('GET', '/api/tasks/'.$task->id, $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token]);

        $response
        ->assertStatus(404);
    }


    //token
    /**
     * Test with token not given or expired while getting one task.
     *
     * @return void
     */
    public function test_read_one_task_token_not_given_or_expired()
    {
        $credential = [];

        $user = User::factory()->create();

        $task = Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => $user->id]);

        $token = $user->createToken('my-app-token')->plainTextToken;

        $token = '';

        $response = $this->json('GET', '/api/tasks/'.$task->id, $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token]);

        $response
        ->assertStatus(401)
        ->assertJsonStructure(['message'])
        ->assertJsonPath('message', "Unauthenticated.");
    }

    ///all user's tasks
    /**
     * Test with all fields correctly filled and valid token given.
     *
     * @return void
     */
    public function test_read_all_tasks_all_fields_correct()
    {
        $credential = [];

        $token = User::factory()->create()->createToken('my-app-token')->plainTextToken;

        $response = $this->json('GET', '/api/tasks',$credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(200)
        ->assertJsonStructure(['tasks']);
    }

    /**
     * Test to have all the user's completed tasks.
     *
     * @return void
     */
    public function test_read_all_tasks_completed()
    {
        $credential = [];

        $user = User::factory()->create();

        $token = $user->createToken('my-app-token')->plainTextToken;

        Task::create(['body'=>"testing task", "completed" => 1, 'user_id' => $user->id]);

        $response = $this->json('GET', '/api/tasks?completed=1',$credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(200)
        ->assertJsonMissing(['completed' => 0]);
    }

    /**
     * Test to have all the user's non-completed tasks.
     *
     * @return void
     */
    public function test_read_all_tasks_non_completed()
    {
        $credential = [];

        $user = User::factory()->create();

        $token = $user->createToken('my-app-token')->plainTextToken;

        Task::create(['body'=>"testing task", "completed" => 0, 'user_id' => $user->id]);

        $response = $this->json('GET', '/api/tasks?completed=1',$credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(200)
        ->assertJsonMissing(['completed' => 1]);
    }


    //token
    /**
     * Test with token not given or expired while getting all tasks.
     *
     * @return void
     */
    public function test_read_all_tasks_token_not_given_or_expired()
    {
        $credential = [
            'body' => 'testing task'
        ];


        $token = '';

        $response = $this->json('GET', '/api/tasks', $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token]);

        $response
        ->assertStatus(401)
        ->assertJsonStructure(['message'])
        ->assertJsonPath('message', "Unauthenticated.");
    }
}
