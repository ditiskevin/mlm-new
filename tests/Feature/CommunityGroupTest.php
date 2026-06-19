<?php

namespace Tests\Feature;

use App\Models\CommunityGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_a_group(): void
    {
        $this->post('/community/groepen', ['name' => 'Test'])->assertRedirect('/login');
        $this->assertSame(0, CommunityGroup::count());
    }

    public function test_member_can_create_a_group_and_auto_follows_it(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/community/groepen', [
            'name' => "Mama's van Utrecht",
            'description' => 'Een groep voor moeders in en rond Utrecht.',
            'color_from' => '#FCE7EB',
            'color_to' => '#EAF5EE',
        ])->assertRedirect(route('community'))->assertSessionHas('success');

        $group = CommunityGroup::first();
        $this->assertNotNull($group);
        $this->assertSame($user->id, $group->user_id);
        $this->assertSame(1, $group->members);
        $this->assertTrue($user->followedGroups()->whereKey($group->id)->exists());
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/community/groepen', ['name' => ''])->assertSessionHasErrors('name');
    }

    public function test_creator_can_delete_their_group(): void
    {
        $user = User::factory()->create();
        $group = CommunityGroup::create(['user_id' => $user->id, 'name' => 'Mijn groep', 'members' => 1, 'color_from' => '#FCE7EB', 'color_to' => '#EAF5EE']);

        $this->actingAs($user)->delete("/community/groepen/{$group->id}")->assertRedirect(route('community'));
        $this->assertNull($group->fresh());
    }

    public function test_other_member_cannot_delete_a_group(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $group = CommunityGroup::create(['user_id' => $owner->id, 'name' => 'Mijn groep', 'members' => 1, 'color_from' => '#FCE7EB', 'color_to' => '#EAF5EE']);

        $this->actingAs($other)->delete("/community/groepen/{$group->id}")->assertForbidden();
        $this->assertNotNull($group->fresh());
    }

    public function test_admin_can_delete_any_group(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $group = CommunityGroup::create(['user_id' => $owner->id, 'name' => 'Mijn groep', 'members' => 1, 'color_from' => '#FCE7EB', 'color_to' => '#EAF5EE']);

        $this->actingAs($admin)->delete("/community/groepen/{$group->id}")->assertRedirect();
        $this->assertNull($group->fresh());
    }

    public function test_following_adjusts_the_member_counter(): void
    {
        $owner = User::factory()->create();
        $follower = User::factory()->create();
        $group = CommunityGroup::create(['user_id' => $owner->id, 'name' => 'Groep', 'members' => 1, 'color_from' => '#FCE7EB', 'color_to' => '#EAF5EE']);

        $this->actingAs($follower)->post("/community/groups/{$group->id}/follow");
        $this->assertSame(2, $group->fresh()->members);

        $this->actingAs($follower)->post("/community/groups/{$group->id}/follow");
        $this->assertSame(1, $group->fresh()->members);
    }
}
