<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_inbox(): void
    {
        $this->get('/berichten')->assertRedirect('/login');
    }

    public function test_member_can_start_a_conversation_and_send_a_message(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        // Start conversation
        $response = $this->actingAs($me)->post("/berichten/start/{$other->id}");
        $conversation = Conversation::first();
        $this->assertNotNull($conversation);
        $response->assertRedirect(route('messages.show', $conversation));
        $this->assertSame(2, $conversation->participants()->count());

        // Send a message
        $this->actingAs($me)->post("/berichten/{$conversation->id}", ['body' => 'Hoi!'])->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $me->id,
            'body' => 'Hoi!',
        ]);
        $this->assertNotNull($conversation->fresh()->last_message_at);
    }

    public function test_starting_conversation_twice_reuses_the_same_one(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($me)->post("/berichten/start/{$other->id}");
        $this->actingAs($me)->post("/berichten/start/{$other->id}");

        $this->assertSame(1, Conversation::count());
    }

    public function test_cannot_start_conversation_with_self(): void
    {
        $me = User::factory()->create();
        $this->actingAs($me)->post("/berichten/start/{$me->id}")->assertSessionHas('error');
        $this->assertSame(0, Conversation::count());
    }

    public function test_non_participant_cannot_view_or_post(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $intruder = User::factory()->create();
        $conversation = Conversation::between($a, $b);

        $this->actingAs($intruder)->get("/berichten/{$conversation->id}")->assertForbidden();
        $this->actingAs($intruder)->post("/berichten/{$conversation->id}", ['body' => 'hack'])->assertForbidden();
    }

    public function test_unread_count_reflects_incoming_messages(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conversation = Conversation::between($a, $b);

        // b sends a message; a hasn't read it.
        $this->actingAs($b)->post("/berichten/{$conversation->id}", ['body' => 'Hallo a!']);

        $this->assertSame(1, $a->fresh()->unreadConversationsCount());
        $this->assertSame(0, $b->fresh()->unreadConversationsCount());

        // a opens the conversation -> marked read.
        $this->actingAs($a)->get("/berichten/{$conversation->id}")->assertOk();
        $this->assertSame(0, $a->fresh()->unreadConversationsCount());
    }

    public function test_message_body_is_required(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conversation = Conversation::between($a, $b);

        $this->actingAs($a)->post("/berichten/{$conversation->id}", ['body' => ''])
            ->assertSessionHasErrors('body');
    }
}
