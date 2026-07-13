<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BlockEffectsTest extends TestCase
{
    use RefreshDatabase;

    private function block(User $blocker, User $blocked): void
    {
        DB::table('user_blocks')->insert([
            'blocker_id' => $blocker->id,
            'blocked_id' => $blocked->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_blocked_members_posts_are_hidden_from_the_feed(): void
    {
        $me = User::factory()->create();
        $blocked = User::factory()->create();
        Post::create(['user_id' => $blocked->id, 'author_name' => $blocked->name, 'avatar_color' => '#F7A8B5', 'body' => 'Verborgen bericht']);
        Post::create(['user_id' => $me->id, 'author_name' => $me->name, 'avatar_color' => '#F7A8B5', 'body' => 'Eigen bericht']);

        $this->block($me, $blocked);

        $this->actingAs($me)->get('/community')->assertInertia(fn ($page) => $page->has('posts', 1)
            ->where('posts.0.body', 'Eigen bericht'));
    }

    public function test_hiding_is_mutual(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        Post::create(['user_id' => $other->id, 'author_name' => $other->name, 'avatar_color' => '#F7A8B5', 'body' => 'Bericht']);

        // The OTHER user blocks me → I also stop seeing their posts.
        $this->block($other, $me);

        $this->actingAs($me)->get('/community')->assertInertia(fn ($page) => $page->has('posts', 0));
    }

    public function test_cannot_start_or_send_messages_when_blocked(): void
    {
        $me = User::factory()->create();
        $blocked = User::factory()->create();
        $this->block($me, $blocked);

        // Starting a conversation is refused.
        $this->actingAs($me)->post("/berichten/start/{$blocked->id}")->assertSessionHas('error');

        // And sending inside an existing conversation is forbidden.
        $conversation = Conversation::between($me, $blocked);
        $this->actingAs($me)->post("/berichten/{$conversation->id}", ['body' => 'hoi'])->assertForbidden();
    }
}
