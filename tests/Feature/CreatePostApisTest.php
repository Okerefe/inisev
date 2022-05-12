<?php

namespace Tests\Feature;

use App\Jobs\NotifySubscribers;
use App\Jobs\SubscriptionAcknowledgement;
use App\Jobs\SubscriptionSuccess;
use App\Models\Subscription;
use App\Notifications\SubscriptionCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreatePostApisTest extends TestCase
{
    use withFaker;
    use RefreshDatabase;


    public function dataForPostErrors()
    {
        return [
            [['title' => "Jo", 'author' => "Some Author", 'content' => "Sample Content"], ['title']],
            [['title' => "Nice Article", 'author' => "fa", 'content' => "Sample Content"], ['author']],
            [['title' => "Nice Article", 'author' => "Jane Writer", 'content' => "Sample"], ['content']],
            [['title' => "Ni", 'author' => "Ja", 'content' => "Sample"], ['title', 'author', 'content']],
        ];
    }


    /** @test
     *  @dataProvider dataForPostErrors
     */
    public function creation_of_posts_returns_expected_validation_errors($formData, $errorFields)
    {
        $response = $this->json('POST', '/post', $formData);

        $response->assertStatus(422);
        $response->assertInvalid($errorFields);

    }


    /** @test */
    public function post_creation_request_successful() {
        Queue::fake();

        $response = $this->json('POST', '/post', [
            'title' => "Sample Post",
            'author' => "Janet Doe",
            'content' => "This is the Content of a Sample Post",
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => "Sample Post",
            'author' => "Janet Doe",
            'content' => "This is the Content of a Sample Post",
        ]);

        Queue::assertPushed(function (NotifySubscribers $notifySubscribers) {
            return $notifySubscribers->post->title === "Sample Post";
        });

    }


}
