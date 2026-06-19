<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_a_member_profile(): void
    {
        $user = User::factory()->create(['name' => 'Sanne', 'bio' => 'Hallo!']);

        $this->get("/leden/{$user->id}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Members/Show')
                ->where('member.name', 'Sanne')
                ->where('isSelf', false)
                ->where('canMessage', false));
    }

    public function test_viewing_own_profile_sets_is_self(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get("/leden/{$user->id}")
            ->assertInertia(fn ($page) => $page->where('isSelf', true)->where('canMessage', false));
    }

    public function test_other_member_can_message_from_profile(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($me)->get("/leden/{$other->id}")
            ->assertInertia(fn ($page) => $page->where('isSelf', false)->where('canMessage', true));
    }

    public function test_profile_lists_the_members_posts(): void
    {
        $user = User::factory()->create();
        Post::create(['user_id' => $user->id, 'author_name' => $user->name, 'avatar_color' => '#F7A8B5', 'body' => 'Mijn eerste bericht']);

        $this->get("/leden/{$user->id}")
            ->assertInertia(fn ($page) => $page->has('posts', 1)->where('posts.0.body', 'Mijn eerste bericht'));
    }
}
