<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublishTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_publish_message()
    {
        $this->withExceptionHandling();

        // create a subscriber
        $topicResponse = $this->post(route('subscribe.create','banking'), [
            'url' => 'http://localhost:9005/test3',
        ]);

        $response = $this->post(route('publish.message',$topicResponse->getData()->topic), [
            'body' => json_encode(['message'=>'hello world']),
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'topic' => $topicResponse->getData()->topic
        ]);
    }
}
