<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostingTest extends TestCase
{
    use RefreshDatabase;

    private function articlePayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Mijn zwangerschapsverhaal',
            'category' => 'Zwangerschap',
            'emoji' => '🤰',
            'excerpt' => 'Een korte samenvatting van mijn ervaring tijdens de zwangerschap.',
            'body' => str_repeat('Dit is een uitgebreid verhaal over mijn ervaring. ', 20),
        ], $overrides);
    }

    public function test_guest_cannot_access_write_form(): void
    {
        $this->get('/blog/schrijven')->assertRedirect('/login');
    }

    public function test_member_can_submit_a_story_which_stays_pending(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/blog', $this->articlePayload())
            ->assertRedirect(route('blog.index'))
            ->assertSessionHas('success');

        $article = Article::first();
        $this->assertNotNull($article);
        $this->assertSame('pending', $article->status);
        $this->assertNull($article->published_at);
        $this->assertSame($user->id, $article->user_id);
        $this->assertGreaterThanOrEqual(1, $article->reading_minutes);
    }

    public function test_pending_story_is_hidden_from_the_public_index(): void
    {
        $user = User::factory()->create();
        Article::create($this->articlePayload([
            'slug' => 'pending-story',
            'author_name' => $user->name,
            'status' => 'pending',
            'user_id' => $user->id,
            'reading_minutes' => 2,
        ]));

        // A separate guest request should not see it.
        $this->get('/blog/pending-story')->assertNotFound();
    }

    public function test_author_can_preview_their_pending_story(): void
    {
        $user = User::factory()->create();
        Article::create($this->articlePayload([
            'slug' => 'pending-story',
            'author_name' => $user->name,
            'status' => 'pending',
            'user_id' => $user->id,
            'reading_minutes' => 2,
        ]));

        $this->actingAs($user)->get('/blog/pending-story')->assertOk();
    }

    public function test_admin_can_approve_a_pending_story(): void
    {
        $admin = User::factory()->admin()->create();
        $article = Article::create($this->articlePayload([
            'slug' => 'pending-story',
            'author_name' => 'Lid',
            'status' => 'pending',
            'reading_minutes' => 2,
        ]));

        $this->actingAs($admin)->patch("/admin/articles/{$article->slug}/approve")->assertRedirect();

        $article->refresh();
        $this->assertSame('published', $article->status);
        $this->assertNotNull($article->published_at);

        // Now publicly visible.
        $this->get('/blog/pending-story')->assertOk();
    }

    public function test_submission_requires_valid_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/blog', $this->articlePayload([
            'title' => '',
            'body' => 'te kort',
        ]))->assertSessionHasErrors(['title', 'body']);

        $this->assertSame(0, Article::count());
    }
}
