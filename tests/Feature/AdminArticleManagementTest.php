<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Welkom bij onze blog',
            'category' => 'Algemeen',
            'author_name' => 'Stephanie',
            'emoji' => '💛',
            'color_from' => '#FCE7EB',
            'color_to' => '#EAF5EE',
            'reading_minutes' => null,
            'excerpt' => 'Een introductie tot ons nieuwe blogplatform voor ouders.',
            'body' => str_repeat('Wij delen graag verhalen en tips met je. ', 30),
            'published' => true,
        ], $overrides);
    }

    public function test_admin_can_create_a_published_article(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/blog', $this->payload())
            ->assertRedirect(route('admin.articles.index'));

        $article = Article::first();
        $this->assertNotNull($article);
        $this->assertSame('published', $article->status);
        $this->assertNotNull($article->published_at);
        // reading_minutes auto-estimated when omitted.
        $this->assertGreaterThanOrEqual(1, $article->reading_minutes);

        // Publicly visible immediately.
        $this->get('/blog/'.$article->slug)->assertOk();
    }

    public function test_admin_can_create_a_hidden_draft(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/blog', $this->payload(['published' => false]));

        $article = Article::first();
        $this->assertSame('pending', $article->status);
        $this->assertNull($article->published_at);
    }

    public function test_admin_can_edit_an_article(): void
    {
        $admin = User::factory()->admin()->create();
        $article = Article::create([
            'title' => 'Welkom bij onze blog',
            'slug' => 'welkom',
            'category' => 'Algemeen',
            'author_name' => 'Stephanie',
            'status' => 'published',
            'reading_minutes' => 3,
            'excerpt' => 'Een introductie tot ons nieuwe blogplatform voor ouders.',
            'body' => str_repeat('Wij delen graag verhalen en tips met je. ', 30),
            'published_at' => now(),
        ]);

        $this->actingAs($admin)->patch('/admin/blog/'.$article->slug, $this->payload([
            'title' => 'Aangepaste titel',
        ]))->assertRedirect(route('admin.articles.index'));

        $this->assertSame('Aangepaste titel', $article->fresh()->title);
    }

    public function test_admin_can_delete_a_member(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();

        $this->actingAs($admin)->delete('/admin/users/'.$member->id)->assertRedirect();
        $this->assertNull($member->fresh());
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->delete('/admin/users/'.$admin->id)->assertSessionHas('error');
        $this->assertNotNull($admin->fresh());
    }

    public function test_non_admin_cannot_create_articles(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/blog', $this->payload())->assertForbidden();
        $this->assertSame(0, Article::count());
    }
}
