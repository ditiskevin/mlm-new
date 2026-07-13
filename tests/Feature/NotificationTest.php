<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Notification;
use App\Models\User;
use App\Support\Notifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifier_creates_and_counts_unread(): void
    {
        $user = User::factory()->create();

        Notifier::send($user, 'test', 'Hallo', 'Een bericht', '/ergens', '🔔');

        $this->assertDatabaseHas('mlm_notifications', ['user_id' => $user->id, 'title' => 'Hallo', 'read_at' => null]);
        $this->assertSame(1, $user->unreadNotificationsCount());
    }

    public function test_no_self_notification(): void
    {
        $user = User::factory()->create();

        Notifier::send(recipient: $user, type: 'test', title: 'Self', actor: $user);

        $this->assertSame(0, Notification::count());
    }

    public function test_sending_a_message_notifies_the_recipient(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conversation = Conversation::between($a, $b);

        $this->actingAs($a)->post("/berichten/{$conversation->id}", ['body' => 'Hoi b!']);

        $this->assertSame(1, $b->unreadNotificationsCount());
        $this->assertSame(0, $a->unreadNotificationsCount());
        $this->assertDatabaseHas('mlm_notifications', ['user_id' => $b->id, 'type' => 'message']);
    }

    public function test_feed_and_mark_all_read(): void
    {
        $user = User::factory()->create();
        Notifier::send($user, 'test', 'Een');
        Notifier::send($user, 'test', 'Twee');

        $this->actingAs($user)->getJson('/notificaties/feed')
            ->assertOk()
            ->assertJsonPath('unread', 2)
            ->assertJsonCount(2, 'notifications');

        $this->actingAs($user)->patch('/notificaties/gelezen')->assertRedirect();
        $this->assertSame(0, $user->unreadNotificationsCount());
    }

    public function test_cannot_touch_someone_elses_notification(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $n = Notifier::send($owner, 'test', 'Privé');

        $this->actingAs($intruder)->patch("/notificaties/{$n->id}/gelezen")->assertForbidden();
        $this->actingAs($intruder)->delete("/notificaties/{$n->id}")->assertForbidden();
    }

    public function test_notifications_page_renders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/notificaties')->assertOk();
    }
}
