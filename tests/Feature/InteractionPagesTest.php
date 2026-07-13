<?php

namespace Tests\Feature;

use App\Models\CommunityGroup;
use App\Models\Notification;
use App\Models\User;
use App\Support\Notifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InteractionPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_index_lists_and_searches(): void
    {
        User::factory()->create(['name' => 'Sanne', 'username' => 'sanne']);
        User::factory()->create(['name' => 'Piet', 'username' => 'piet']);

        $this->get('/leden')->assertOk()->assertInertia(fn ($p) => $p->component('Members/Index')->has('members', 2));
        $this->get('/leden?q=sanne')->assertInertia(fn ($p) => $p->has('members', 1)->where('members.0.name', 'Sanne'));
    }

    public function test_group_can_be_opened(): void
    {
        $owner = User::factory()->create();
        $group = CommunityGroup::create(['user_id' => $owner->id, 'name' => 'Utrecht', 'members' => 1, 'color_from' => '#FCE7EB', 'color_to' => '#EAF5EE']);

        $this->get("/community/groepen/{$group->id}")
            ->assertOk()
            ->assertInertia(fn ($p) => $p->component('Community/Group')->where('group.name', 'Utrecht'));
    }

    public function test_followers_and_following_lists_render(): void
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        DB::table('user_follows')->insert(['follower_id' => $b->id, 'followed_id' => $a->id, 'created_at' => now(), 'updated_at' => now()]);

        $this->get("/leden/{$a->id}/volgers")->assertOk()->assertInertia(fn ($p) => $p->has('people', 1));
        $this->get("/leden/{$a->id}/volgend")->assertOk()->assertInertia(fn ($p) => $p->has('people', 0));
    }

    public function test_mutual_follow_shows_as_friends(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        DB::table('user_follows')->insert([
            ['follower_id' => $me->id, 'followed_id' => $other->id, 'created_at' => now(), 'updated_at' => now()],
            ['follower_id' => $other->id, 'followed_id' => $me->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->actingAs($me)->get("/leden/{$other->id}")
            ->assertInertia(fn ($p) => $p->where('areFriends', true)->where('connections.friends', 1));
    }

    public function test_marking_a_single_notification_read(): void
    {
        $user = User::factory()->create();
        $n = Notifier::send($user, 'test', 'Hoi');

        $this->actingAs($user)->patch("/notificaties/{$n->id}/gelezen")->assertRedirect();
        $this->assertNotNull($n->fresh()->read_at);
        $this->assertSame(0, $user->unreadNotificationsCount());
    }
}
