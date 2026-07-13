<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class BlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_toggle_block(): void
    {
        $target = User::factory()->create();

        $this->post(route('members.block', $target->id))->assertRedirect('/login');
        $this->assertDatabaseCount('user_blocks', 0);
    }

    public function test_member_can_block_another_member(): void
    {
        $me = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($me)->post(route('members.block', $target->id))->assertRedirect();

        $this->assertDatabaseHas('user_blocks', [
            'blocker_id' => $me->id,
            'blocked_id' => $target->id,
        ]);
    }

    public function test_toggling_again_unblocks(): void
    {
        $me = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($me)->post(route('members.block', $target->id));
        $this->actingAs($me)->post(route('members.block', $target->id));

        $this->assertDatabaseMissing('user_blocks', [
            'blocker_id' => $me->id,
            'blocked_id' => $target->id,
        ]);
    }

    public function test_cannot_block_self(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)->post(route('members.block', $me->id))->assertSessionHas('error');

        $this->assertDatabaseCount('user_blocks', 0);
    }

    public function test_blocked_list_page_renders(): void
    {
        $me = User::factory()->create();
        $target = User::factory()->create(['name' => 'Geblokkeerd Lid']);

        $this->actingAs($me)->post(route('members.block', $target->id));

        $this->actingAs($me)->get(route('members.blocked'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Members/Blocked')
                ->has('blocked', 1)
                ->where('blocked.0.id', $target->id)
                ->where('blocked.0.name', 'Geblokkeerd Lid')
            );
    }

    public function test_blocked_list_requires_auth(): void
    {
        $this->get(route('members.blocked'))->assertRedirect('/login');
    }
}
