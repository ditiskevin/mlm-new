<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(User $user, string $body = 'Hallo community'): Post
    {
        return Post::create([
            'user_id' => $user->id,
            'author_name' => $user->name,
            'avatar_color' => '#F7A8B5',
            'body' => $body,
        ]);
    }

    public function test_guest_cannot_toggle_follow(): void
    {
        $target = User::factory()->create();

        $this->post(route('follow.toggle', $target->id))->assertRedirect('/login');
        $this->assertDatabaseCount('user_follows', 0);
    }

    public function test_member_can_follow_another_member(): void
    {
        $me = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($me)->post(route('follow.toggle', $target->id))->assertRedirect();

        $this->assertDatabaseHas('user_follows', [
            'follower_id' => $me->id,
            'followed_id' => $target->id,
        ]);
    }

    public function test_toggling_again_unfollows(): void
    {
        $me = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($me)->post(route('follow.toggle', $target->id));
        $this->actingAs($me)->post(route('follow.toggle', $target->id));

        $this->assertDatabaseMissing('user_follows', [
            'follower_id' => $me->id,
            'followed_id' => $target->id,
        ]);
    }

    public function test_cannot_follow_self(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)->post(route('follow.toggle', $me->id))->assertSessionHas('error');

        $this->assertDatabaseCount('user_follows', 0);
    }

    public function test_followed_user_gets_a_notification(): void
    {
        $me = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($me)->post(route('follow.toggle', $target->id));

        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $target->id,
            'actor_id' => $me->id,
            'type' => 'follow',
        ]);
    }

    public function test_following_feed_shows_only_followed_members_posts(): void
    {
        $me = User::factory()->create();
        $followed = User::factory()->create();
        $stranger = User::factory()->create();

        $this->makePost($followed, 'Bericht van gevolgd lid');
        $this->makePost($stranger, 'Bericht van vreemde');

        $this->actingAs($me)->post(route('follow.toggle', $followed->id));

        $this->actingAs($me)->get(route('follow.following'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Feed/Following')
                ->has('posts', 1)
                ->where('posts.0.body', 'Bericht van gevolgd lid')
                ->where('posts.0.author_id', $followed->id)
                ->where('count', 1)
            );
    }

    public function test_following_feed_requires_auth(): void
    {
        $this->get(route('follow.following'))->assertRedirect('/login');
    }
}
