<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_a_poll(): void
    {
        $this->post('/polls', [
            'question' => 'Borstvoeding of flesvoeding?',
            'options' => ['Borst', 'Fles'],
        ])->assertRedirect('/login');

        $this->assertSame(0, Poll::count());
    }

    public function test_guest_cannot_vote(): void
    {
        $poll = $this->makePoll();
        $option = $poll->options()->first();

        $this->post("/polls/{$poll->id}/stem", ['poll_option_id' => $option->id])
            ->assertRedirect('/login');

        $this->assertSame(0, PollVote::count());
    }

    public function test_member_can_create_a_poll_with_three_options(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/polls', [
            'question' => 'Wat is jullie favoriete uitje?',
            'options' => ['Dierentuin', 'Strand', 'Speeltuin'],
        ])->assertRedirect();

        $this->assertDatabaseHas('polls', [
            'question' => 'Wat is jullie favoriete uitje?',
            'user_id' => $user->id,
            'author_name' => $user->name,
        ]);

        $poll = Poll::first();
        $this->assertSame(3, PollOption::where('poll_id', $poll->id)->count());
    }

    public function test_creating_a_poll_ignores_empty_options(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/polls', [
            'question' => 'Ja of nee?',
            'options' => ['Ja', '', 'Nee', '   '],
        ])->assertRedirect();

        $this->assertSame(2, PollOption::count());
    }

    public function test_creating_a_poll_with_fewer_than_two_options_fails(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/polls', [
            'question' => 'Alleen een optie?',
            'options' => ['Enige optie'],
        ])->assertSessionHasErrors('options');

        $this->assertSame(0, Poll::count());
    }

    public function test_voting_records_a_vote(): void
    {
        $user = User::factory()->create();
        $poll = $this->makePoll();
        $option = $poll->options()->first();

        $this->actingAs($user)->post("/polls/{$poll->id}/stem", [
            'poll_option_id' => $option->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_voting_again_changes_the_vote(): void
    {
        $user = User::factory()->create();
        $poll = $this->makePoll();
        [$first, $second] = $poll->options()->get()->all();

        $this->actingAs($user)->post("/polls/{$poll->id}/stem", ['poll_option_id' => $first->id]);
        $this->actingAs($user)->post("/polls/{$poll->id}/stem", ['poll_option_id' => $second->id]);

        // Still exactly one vote row for this user, now pointing at the second option.
        $this->assertSame(1, PollVote::where('poll_id', $poll->id)->where('user_id', $user->id)->count());
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $second->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_owner_can_delete_their_poll(): void
    {
        $user = User::factory()->create();
        $poll = $this->makePoll($user);

        $this->actingAs($user)->delete("/polls/{$poll->id}")->assertRedirect();
        $this->assertNull($poll->fresh());
    }

    public function test_admin_can_delete_any_poll(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $poll = $this->makePoll($owner);

        $this->actingAs($admin)->delete("/polls/{$poll->id}")->assertRedirect();
        $this->assertNull($poll->fresh());
    }

    public function test_non_owner_cannot_delete_a_poll(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $poll = $this->makePoll($owner);

        $this->actingAs($other)->delete("/polls/{$poll->id}")->assertForbidden();
        $this->assertNotNull($poll->fresh());
    }

    private function makePoll(?User $owner = null): Poll
    {
        $owner ??= User::factory()->create();

        $poll = Poll::create([
            'user_id' => $owner->id,
            'question' => 'Test poll?',
            'author_name' => $owner->name,
            'avatar_color' => '#F7A8B5',
        ]);

        $poll->options()->create(['label' => 'Optie A', 'position' => 0]);
        $poll->options()->create(['label' => 'Optie B', 'position' => 1]);

        return $poll;
    }
}
