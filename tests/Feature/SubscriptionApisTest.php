<?php

namespace Tests\Feature;

use App\Jobs\SubscriptionAcknowledgement;
use App\Jobs\SubscriptionSuccess;
use App\Models\Subscription;
use App\Notifications\SubscriptionCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubscriptionApisTest extends TestCase
{
    use withFaker;
    use RefreshDatabase;


    public function dataForSubscriptionErrors()
    {
        return [
            [['name' => "Jo", 'email' => "john@doe.com"], ['name']],
            [['email' => "wrongemail", 'name' => "Jane Doe"], ['email']],
            [['name' => "wo",'email' => "wrong"], ['name', 'email']],
        ];
    }


    /** @test
     *  @dataProvider dataForSubscriptionErrors
     */
    public function subscription_returns_expected_validation_errors($formData, $errorFields)
    {
        $response = $this->json('POST', '/subscribe', $formData);

        $response->assertStatus(422);
        $response->assertInvalid($errorFields);

    }


    /** @test */
    public function user_subscription_request_successful() {
        Queue::fake();

        $response = $this->json('POST', '/subscribe', [
            'name' => "John Doe",
            'email' => "joe@gmail.com",
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('subscriptions', [
            'name' => "John Doe",
            'email' => "joe@gmail.com",
        ]);

        //$this->withoutExceptionHandling();

        Queue::assertPushed(function (SubscriptionSuccess $acknowledgement) {
            return $acknowledgement->subscription->email === "joe@gmail.com";
        });

    }
}
