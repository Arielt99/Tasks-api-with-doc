<?php

namespace Tests\Feature\UserTests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test with all fields correctly filled.
     *
     * @return void
     */
    public function test_register_all_correct()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 'Password21',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(201)
        ->assertJsonStructure(['user', 'token']);
    }


    //email field
    /**
     * Test with email fields empty.
     *
     * @return void
     */
    public function test_register_email_empty()
    {
        $credential = [
            'email' => '',
            'password' => 'Password21',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email field is required."]);
    }

    /**
     * Test with email wrong format.
     *
     * @return void
     */
    public function test_register_email_wrong_format()
    {
        $credential = [
            'email' => 'emailtest3',
            'password' => 'Password21',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email must be a valid email address."]);
    }

    /**
     * Test with email already taken.
     *
     * @return void
     */
    public function test_register_email_already_taken()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 'Password21',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response->
        assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email has already been taken."]);
    }

    /**
     * Test with email is not a string.
     *
     * @return void
     */
    public function test_register_email_not_a_string()
    {
        $credential = [
            'email' => 123456789,
            'password' => 'Password21',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email must be a string.","The email must be a valid email address."]);
    }


    //password field
    /**
     * Test with password fields empty.
     *
     * @return void
     */
    public function test_register_password_empty()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => '',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password field is required."]);
    }

    /**
     * Test with password wrong format.
     *
     * @return void
     */
    public function test_register_password_wrong_format()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 'password',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password format is invalid."]);
    }

    /**
     * Test with password too short.
     *
     * @return void
     */
    public function test_register_password_too_short()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 'Pa21',
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password must be at least 6 characters."]);
    }

    /**
     * Test with password is not a string.
     *
     * @return void
     */
    public function test_register_password_not_a_string()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 123456789,
            'name' => 'John Doe'
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password must be a string.","The password format is invalid."]);
    }


    //name field
    /**
     * Test with name fields empty.
     *
     * @return void
     */
    public function test_register_name_empty()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 'Password21',
            'name' => ''
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('name')
        ->assertJsonPath('errors.name', ["The name field is required."]);
    }

    /**
     * Test with name is not a string.
     *
     * @return void
     */
    public function test_register_name_not_a_string()
    {
        $credential = [
            'email' => 'emailtest3@mail.com',
            'password' => 'Password21',
            'name' => 231
        ];

        $response = $this->json('POST', '/api/register', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('name')
        ->assertJsonPath('errors.name', ["The name must be a string."]);
    }
}
