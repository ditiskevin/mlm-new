<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Mentions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_assigns_a_unique_username(): void
    {
        $this->post('/register', [
            'name' => 'Sanne de Vries',
            'email' => 'sanne@example.com',
            'parent_type' => 'ouder',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'sanne@example.com')->first();
        $this->assertNotNull($user->username);
        $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $user->username);
    }

    public function test_unique_username_avoids_collisions(): void
    {
        User::factory()->create(['username' => 'sanne']);
        $this->assertSame('sanne2', User::uniqueUsername('Sanne'));
    }

    public function test_mentioning_a_member_notifies_them(): void
    {
        $actor = User::factory()->create();
        $mentioned = User::factory()->create(['username' => 'lisa']);

        $ids = Mentions::notify('Hoi @lisa, wat denk jij?', $actor, '/community', 'een bericht');

        $this->assertSame([$mentioned->id], $ids);
        $this->assertDatabaseHas('mlm_notifications', ['user_id' => $mentioned->id, 'type' => 'mention']);
    }

    public function test_mention_via_community_post(): void
    {
        $author = User::factory()->create();
        $mentioned = User::factory()->create(['username' => 'naomi']);

        $this->actingAs($author)->post('/community/posts', ['body' => 'Kijk eens @naomi!']);

        $this->assertSame(1, $mentioned->unreadNotificationsCount());
    }

    public function test_no_self_mention_and_unknown_handles_ignored(): void
    {
        $actor = User::factory()->create(['username' => 'zelf']);

        $ids = Mentions::notify('@zelf en @bestaatniet', $actor, '/community');

        $this->assertSame([], $ids);
    }
}
