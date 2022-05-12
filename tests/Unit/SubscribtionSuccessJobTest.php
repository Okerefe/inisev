<?php

namespace Tests\Unit;

use App\Jobs\SubscriptionSuccess;
use App\Models\Subscription;
use App\Notifications\SubscriptionCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubscribtionSuccessJobTest extends TestCase
{
    use withFaker;
    use RefreshDatabase;

    /** @test */
    public function subscription_job_sends_notification_successful()
    {
        //$this->withoutExceptionHandling();
        Notification::fake();
        $subscription = Subscription::factory()->create();
        (new SubscriptionSuccess($subscription))->handle();

        Notification::assertSentTo(
            $subscription,
            SubscriptionCreated::class,
        );

    }

}
