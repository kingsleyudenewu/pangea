<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_subscription()
    {
        $this->withExceptionHandling();
        $response = $this->post(route('subscribe.create','banking'), [
            'url' => 'http://localhost:9005/test3',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('http://localhost:9005/test3', $response->getData()->url);
        $response->assertJsonFragment([
            'url' => 'http://localhost:9005/test3'
        ]);
    }
}
