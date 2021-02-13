<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test with all fiels correctly filled and existing data.
     *
     * @return void
     */
    public function test_login_all_correct()
    {
        User::create([
            'email' => 'emailtest@mail.com',
            "name" => 'John Doe',
            'password' => bcrypt('Password21'),
         ]);

        $credential = [
            'email' => 'emailtest@mail.com',
            'password' => 'Password21'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response->assertStatus(201)->assertJsonStructure(['user', 'token']);;
    }
//email field
    /**
     * Test with email fields empty.
     *
     * @return void
     */
    public function test_login_email_empty()
    {
        $credential = [
            'email' => '',
            'password' => 'Password21'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email field is required."]);
    }

    /**
     * Test with email fields wrong format.
     *
     * @return void
     */
    public function test_login_email_wrong_format()
    {
        $credential = [
            'email' => 'emailtest',
            'password' => 'Password21'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email must be a valid email address."]);
    }

    /**
     * Test with email not matching the records.
     *
     * @return void
     */
    public function test_login_email_not_matching()
    {
        $credential = [
            'email' => 'emailtest2@mail.com',
            'password' => 'Password21'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response->
        assertStatus(422)
        ->assertJsonValidationErrors('email')
        ->assertJsonPath('errors.email', ["The email do not match our records."]);
    }

    /**
     * Test with email fields is not a string.
     *
     * @return void
     */
    public function test_login_email_not_a_string()
    {
        $credential = [
            'email' => 123456789,
            'password' => 'Password21'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

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
    public function test_login_password_empty()
    {
        $credential = [
            'email' => 'emailtest@mail.com',
            'password' => ''
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password field is required."]);
    }

    /**
     * Test with password fields wrong format.
     *
     * @return void
     */
    public function test_login_password_wrong_format()
    {
        $credential = [
            'email' => 'emailtest@mail.com',
            'password' => 'password'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password format is invalid."]);
    }

    /**
     * Test with password fields too short.
     *
     * @return void
     */
    public function test_login_password_too_short()
    {
        $credential = [
            'email' => 'emailtest@mail.com',
            'password' => 'Pa21'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password must be at least 6 characters."]);
    }

    /**
     * Test with email not matching the records.
     *
     * @return void
     */
    public function test_login_password_not_matching()
    {
        $credential = [
            'email' => 'emailtest@mail.com',
            'password' => 'Password2'
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response->
        assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password do not match our records."]);
    }

    /**
     * Test with password fields is not a string.
     *
     * @return void
     */
    public function test_login_password_not_a_string()
    {
        $credential = [
            'email' => 'emailtest@mail.com',
            'password' => 123456789
        ];

        $response = $this->json('POST', '/api/login', $credential, ['Accept' => 'application/json']);

        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password')
        ->assertJsonPath('errors.password', ["The password must be a string.","The password format is invalid."]);
    }
}
