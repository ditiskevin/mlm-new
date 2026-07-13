<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(): Post
    {
        return Post::create([
            'author_name' => 'X',
            'avatar_color' => '#F7A8B5',
            'body' => 'hi',
        ]);
    }

    public function test_guest_cannot_toggle_a_bookmark(): void
    {
        $post = $this->makePost();

        $this->post('/bookmarks/toggle', [
            'type' => 'post',
            'id' => $post->id,
        ])->assertRedirect('/login');

        $this->assertDatabaseCount('bookmarks', 0);
    }

    public function test_member_can_toggle_a_post_on_and_off(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        // On
        $this->actingAs($user)->post('/bookmarks/toggle', [
            'type' => 'post',
            'id' => $post->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'bookmarkable_type' => Post::class,
            'bookmarkable_id' => $post->id,
        ]);

        // Off — toggling again removes it
        $this->actingAs($user)->post('/bookmarks/toggle', [
            'type' => 'post',
            'id' => $post->id,
        ])->assertRedirect();

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'bookmarkable_type' => Post::class,
            'bookmarkable_id' => $post->id,
        ]);
    }

    public function test_duplicate_toggle_removes_the_bookmark(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $payload = ['type' => 'post', 'id' => $post->id];
        $this->actingAs($user)->post('/bookmarks/toggle', $payload);
        $this->actingAs($user)->post('/bookmarks/toggle', $payload);

        $this->assertDatabaseCount('bookmarks', 0);
    }

    public function test_toggle_validates_type(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/bookmarks/toggle', [
            'type' => 'banaan',
            'id' => 1,
        ])->assertSessionHasErrors('type');
    }

    public function test_bookmarks_index_renders(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/bookmarks/toggle', [
            'type' => 'post',
            'id' => $post->id,
        ]);

        $this->actingAs($user)->get('/bewaard')->assertOk();
    }
}
