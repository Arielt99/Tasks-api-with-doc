<?php

namespace Tests\Feature\TasksTests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test with all fields correctly filled and valid token given.
     *
     * @return void
     */
    public function test_create_task_all_correct()
    {
        $user = User::factory()->create();

        $token = $user->createToken('my-app-token')->plainTextToken;

        $credential = [
            'body' => 'testing task'
        ];

        $response = $this->json('POST', '/api/tasks', $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(201)
        ->assertJsonStructure(['message'])
        ->assertJsonPath('message', "Task created succesfully.");
    }


    //body field
    /**
     * Test with body fields empty.
     *
     * @return void
     */
    public function test_create_task_body_empty()
    {
        $credential = [
            'body' => ''
        ];

        $user= User::factory()->create();

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = $this->json('POST', '/api/tasks', $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('body')
        ->assertJsonPath('errors.body', ["The body field is required."]);
    }

    /**
     * Test with body is not a string.
     *
     * @return void
     */
    public function test_create_task_body_not_a_string()
    {
        $credential = [
            'body' => 231
        ];

        $user= User::factory()->create();

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = $this->json('POST', '/api/tasks', $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('body')
        ->assertJsonPath('errors.body', ["The body must be a string."]);
    }

    //token
    /**
     * Test with token not given or expired.
     *
     * @return void
     */
    public function test_create_task_token_not_given_or_expired()
    {
        $credential = [
            'body' => 'testing task'
        ];


        $token = '';

        $response = $this->json('POST', '/api/tasks', $credential, ['Accept' => 'application/json', 'Authorization' => 'Bearer '.$token,]);

        $response
        ->assertStatus(401)
        ->assertJsonStructure(['message'])
        ->assertJsonPath('message', "Unauthenticated.");
    }
}
