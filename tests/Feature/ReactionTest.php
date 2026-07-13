<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReactionTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(?User $author = null): Post
    {
        return Post::create([
            'author_name' => 'X',
            'avatar_color' => '#F7A8B5',
            'body' => 'hi',
            'user_id' => $author?->id,
        ]);
    }

    public function test_guest_cannot_react(): void
    {
        $post = $this->makePost();

        $this->post('/reacties-emoji/toggle', [
            'type' => 'post',
            'id' => $post->id,
            'emoji' => '💛',
        ])->assertRedirect('/login');

        $this->assertDatabaseCount('reactions', 0);
    }

    public function test_member_can_react_to_a_post(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/reacties-emoji/toggle', [
            'type' => 'post',
            'id' => $post->id,
            'emoji' => '💛',
        ])->assertRedirect();

        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'reactable_type' => Post::class,
            'reactable_id' => $post->id,
            'emoji' => '💛',
        ]);
    }

    public function test_same_emoji_again_removes_the_reaction(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $payload = ['type' => 'post', 'id' => $post->id, 'emoji' => '💛'];
        $this->actingAs($user)->post('/reacties-emoji/toggle', $payload);
        $this->actingAs($user)->post('/reacties-emoji/toggle', $payload);

        $this->assertDatabaseCount('reactions', 0);
    }

    public function test_different_emoji_replaces_the_reaction(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/reacties-emoji/toggle', [
            'type' => 'post', 'id' => $post->id, 'emoji' => '💛',
        ]);
        $this->actingAs($user)->post('/reacties-emoji/toggle', [
            'type' => 'post', 'id' => $post->id, 'emoji' => '❤️',
        ]);

        // Still exactly one row, now the new emoji.
        $this->assertSame(1, Reaction::count());
        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'reactable_type' => Post::class,
            'reactable_id' => $post->id,
            'emoji' => '❤️',
        ]);
    }

    public function test_post_author_is_notified_on_a_new_reaction(): void
    {
        $author = User::factory()->create();
        $post = $this->makePost($author);
        $reactor = User::factory()->create();

        $this->actingAs($reactor)->post('/reacties-emoji/toggle', [
            'type' => 'post',
            'id' => $post->id,
            'emoji' => '🤗',
        ]);

        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $author->id,
            'actor_id' => $reactor->id,
            'type' => 'reaction',
        ]);
    }

    public function test_invalid_emoji_is_rejected(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/reacties-emoji/toggle', [
            'type' => 'post',
            'id' => $post->id,
            'emoji' => '🍌',
        ])->assertSessionHasErrors('emoji');

        $this->assertDatabaseCount('reactions', 0);
    }
}
