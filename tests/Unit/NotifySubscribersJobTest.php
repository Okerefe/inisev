<?php

namespace Tests\Unit;

use App\Jobs\NotifySubscribers;
use App\Jobs\SubscriptionSuccess;
use App\Models\Post;
use App\Models\Subscription;
use App\Notifications\NewPost;
use App\Notifications\SubscriptionCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifySubscribersJobTest extends TestCase
{
    use withFaker;
    use RefreshDatabase;

    /** @test */
    public function notification_job_sends_notification_to_all_subscriberssuccessful()
    {
        //$this->withoutExceptionHandling();
        Notification::fake();

        $sub1 = Subscription::factory()->create();
        $sub2 = Subscription::factory()->create();
        $sub3 = Subscription::factory()->create();
        $sub4 = Subscription::factory()->create();
        $post = Post::factory()->create();

        (new NotifySubscribers($post))->handle();

        Notification::assertSentTo(
            [$sub1, $sub2, $sub3, $sub4],
            NewPost::class,
        );

    }


}
