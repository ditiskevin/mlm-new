<?php

namespace Tests\Feature;

use App\Models\CommunityGroup;
use App\Models\ForumCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_admin_index_pages_load(): void
    {
        $admin = $this->admin();

        foreach ([
            '/admin', '/admin/leden', '/admin/berichten', '/admin/reacties',
            '/admin/forum-onderwerpen', '/admin/forum-reacties', '/admin/marktplaats',
            '/admin/oppasprofielen', '/admin/groepen', '/admin/forum-categorieen',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_non_admin_is_forbidden(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin/leden')->assertForbidden();
        $this->actingAs($user)->get('/admin/groepen')->assertForbidden();
    }

    public function test_admin_can_edit_a_member(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create(['name' => 'Oud']);

        $this->actingAs($admin)->patch("/admin/leden/{$member->id}", [
            'name' => 'Nieuw',
            'email' => 'nieuw@example.com',
            'parent_type' => 'ouder',
            'parenting_role' => 'moeder',
            'is_admin' => true,
        ])->assertRedirect(route('admin.users.index'));

        $member->refresh();
        $this->assertSame('Nieuw', $member->name);
        $this->assertSame('nieuw@example.com', $member->email);
        $this->assertTrue((bool) $member->is_admin);
    }

    public function test_admin_cannot_remove_own_admin_via_edit(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->patch("/admin/leden/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'parent_type' => 'ouder',
            'is_admin' => false,
        ]);

        $this->assertTrue((bool) $admin->fresh()->is_admin);
    }

    public function test_admin_can_crud_a_community_group(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/groepen', [
            'name' => 'Nieuwe groep',
            'description' => 'Een groep',
            'members' => 5,
            'color_from' => '#FCE7EB',
            'color_to' => '#EAF5EE',
        ])->assertRedirect(route('admin.groups.index'));

        $group = CommunityGroup::first();
        $this->assertSame('Nieuwe groep', $group->name);

        $this->actingAs($admin)->patch("/admin/groepen/{$group->id}", [
            'name' => 'Aangepast',
            'members' => 9,
        ])->assertRedirect(route('admin.groups.index'));
        $this->assertSame('Aangepast', $group->fresh()->name);

        $this->actingAs($admin)->delete("/admin/groepen/{$group->id}")->assertRedirect();
        $this->assertNull($group->fresh());
    }

    public function test_admin_can_crud_a_forum_category(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/forum-categorieen', [
            'name' => 'Zwangerschap',
            'description' => 'Alles over de zwangerschap.',
            'emoji' => '🤰',
        ])->assertRedirect(route('admin.forum-categories.index'));

        $cat = ForumCategory::first();
        $this->assertSame('zwangerschap', $cat->slug);

        $this->actingAs($admin)->patch("/admin/forum-categorieen/{$cat->slug}", [
            'name' => 'Zwangerschap',
            'description' => 'Bijgewerkt.',
            'emoji' => '🤰',
        ])->assertRedirect(route('admin.forum-categories.index'));
        $this->assertSame('Bijgewerkt.', $cat->fresh()->description);

        $this->actingAs($admin)->delete("/admin/forum-categorieen/{$cat->slug}")->assertRedirect();
        $this->assertNull($cat->fresh());
    }
}
