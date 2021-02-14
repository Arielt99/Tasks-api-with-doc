<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GlobalTest extends TestCase
{
    /**
     * Test for 404.
     *
     * @return void
     */
    public function test_404_not_found()
    {
        $response = $this->get('/test');

        $response->assertStatus(404);
    }
}
