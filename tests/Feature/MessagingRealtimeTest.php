<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MessagingRealtimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_sending_a_message_dispatches_the_broadcast_event(): void
    {
        Event::fake([MessageSent::class]);

        $a = User::factory()->create();
        $b = User::factory()->create();
        $conversation = Conversation::between($a, $b);

        $this->actingAs($a)->post("/berichten/{$conversation->id}", ['body' => 'Hallo realtime!']);

        Event::assertDispatched(MessageSent::class, fn (MessageSent $e) => $e->message->body === 'Hallo realtime!'
            && $e->message->conversation_id === $conversation->id);
    }

    public function test_event_broadcasts_on_the_private_conversation_channel(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conversation = Conversation::between($a, $b);
        $message = $conversation->messages()->create(['user_id' => $a->id, 'body' => 'Hoi']);

        $event = new MessageSent($message);
        $channels = $event->broadcastOn();

        $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
        $this->assertSame('private-conversation.'.$conversation->id, (string) $channels[0]);
        $this->assertSame('message.sent', $event->broadcastAs());

        $payload = $event->broadcastWith();
        $this->assertSame($message->id, $payload['id']);
        $this->assertSame($a->id, $payload['sender_id']);
        $this->assertSame('Hoi', $payload['body']);
    }

    public function test_reverb_key_is_exposed_at_runtime_for_the_browser_client(): void
    {
        // The client reads the key from a meta tag, so realtime needs no build args.
        config(['broadcasting.connections.reverb.key' => 'runtime-key-123']);

        $this->get('/')
            ->assertOk()
            ->assertSee('name="reverb-key"', false)
            ->assertSee('runtime-key-123', false);
    }

    public function test_channel_authorization_predicate_allows_only_participants(): void
    {
        // This is the exact predicate the conversation.{id} broadcast channel
        // uses to authorize subscribers (see routes/channels.php).
        $a = User::factory()->create();
        $b = User::factory()->create();
        $intruder = User::factory()->create();
        $conversation = Conversation::between($a, $b);

        $this->assertTrue($conversation->hasParticipant($a));
        $this->assertTrue($conversation->hasParticipant($b));
        $this->assertFalse($conversation->hasParticipant($intruder));
    }
}
