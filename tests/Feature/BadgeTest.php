<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Support\BadgeService;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(BadgeSeeder::class);
    }

    public function test_award_grants_badge_and_creates_notification(): void
    {
        $user = User::factory()->create();

        $result = BadgeService::award($user, 'welkom');

        $this->assertTrue($result);
        $this->assertDatabaseHas('user_badges', ['user_id' => $user->id]);
        $this->assertSame(1, $user->badges()->count());

        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $user->id,
            'type' => 'badge',
        ]);
    }

    public function test_award_is_idempotent(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(BadgeService::award($user, 'welkom'));
        $this->assertFalse(BadgeService::award($user, 'welkom'));

        $this->assertSame(1, DB::table('user_badges')->where('user_id', $user->id)->count());
    }

    public function test_award_with_unknown_key_is_noop(): void
    {
        $user = User::factory()->create();

        $this->assertFalse(BadgeService::award($user, 'bestaat-niet'));
        $this->assertSame(0, $user->badges()->count());
    }

    public function test_evaluate_awards_eerste_bericht_after_a_post(): void
    {
        $user = User::factory()->create();

        Post::create([
            'user_id' => $user->id,
            'author_name' => $user->name,
            'avatar_color' => '#F7A8B5',
            'body' => 'Mijn eerste bericht in de community',
        ]);

        BadgeService::evaluate($user);

        $this->assertTrue(
            $user->badges()->where('key', 'eerste-bericht')->exists()
        );
    }

    public function test_evaluate_awards_sociaal_after_ten_followers(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $follower = User::factory()->create();

            DB::table('user_follows')->insert([
                'follower_id' => $follower->id,
                'followed_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->assertSame(10, $user->followers()->count());

        BadgeService::evaluate($user);

        $this->assertTrue(
            $user->badges()->where('key', 'sociaal')->exists()
        );
    }
}
