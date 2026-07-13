<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a published article, optionally owned by an author user.
     */
    private function makeArticle(?User $author = null): Article
    {
        return Article::create([
            'user_id' => $author?->id,
            'title' => 'Ons hechtingsverhaal',
            'slug' => 'ons-hechtingsverhaal',
            'category' => 'Opvoeding',
            'author_name' => $author?->name ?? 'Stephanie van der Kooij',
            'status' => 'published',
            'published_at' => now(),
            'excerpt' => 'Een kort verhaal over hechting en vertrouwen tussen ouder en kind.',
            'body' => str_repeat('Dit is een uitgebreid verhaal over hechting. ', 20),
            'reading_minutes' => 3,
        ]);
    }

    public function test_guest_cannot_comment_and_is_redirected_to_login(): void
    {
        $article = $this->makeArticle();

        $this->post("/blog/{$article->slug}/reacties", ['body' => 'Wat een mooi verhaal!'])
            ->assertRedirect('/login');

        $this->assertSame(0, ArticleComment::count());
    }

    public function test_member_can_comment(): void
    {
        $article = $this->makeArticle();
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post("/blog/{$article->slug}/reacties", ['body' => 'Herkenbaar, dankjewel!'])
            ->assertRedirect();

        $this->assertDatabaseHas('article_comments', [
            'article_id' => $article->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'body' => 'Herkenbaar, dankjewel!',
        ]);
    }

    public function test_comment_requires_a_body(): void
    {
        $article = $this->makeArticle();
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post("/blog/{$article->slug}/reacties", ['body' => ''])
            ->assertSessionHasErrors('body');

        $this->assertSame(0, ArticleComment::count());
    }

    public function test_article_author_gets_notified_when_someone_else_comments(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post("/blog/{$article->slug}/reacties", ['body' => 'Wat dapper van je om dit te delen.']);

        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $author->id,
            'type' => 'blog_comment',
        ]);
    }

    public function test_author_does_not_get_notified_for_their_own_comment(): void
    {
        $author = User::factory()->create();
        $article = $this->makeArticle($author);

        $this->actingAs($author)
            ->post("/blog/{$article->slug}/reacties", ['body' => 'Een aanvulling op mijn eigen verhaal.']);

        $this->assertDatabaseMissing('mlm_notifications', [
            'user_id' => $author->id,
            'type' => 'blog_comment',
        ]);
    }

    public function test_commenter_can_delete_their_own_comment(): void
    {
        $article = $this->makeArticle();
        $member = User::factory()->create();
        $comment = ArticleComment::create([
            'article_id' => $article->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'avatar_color' => '#CFE3F5',
            'body' => 'Mijn reactie.',
        ]);

        $this->actingAs($member)
            ->delete("/blog-reacties/{$comment->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('article_comments', ['id' => $comment->id]);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $article = $this->makeArticle();
        $member = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $comment = ArticleComment::create([
            'article_id' => $article->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'avatar_color' => '#CFE3F5',
            'body' => 'Reactie van een lid.',
        ]);

        $this->actingAs($admin)
            ->delete("/blog-reacties/{$comment->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('article_comments', ['id' => $comment->id]);
    }

    public function test_non_owner_cannot_delete_someone_elses_comment(): void
    {
        $article = $this->makeArticle();
        $member = User::factory()->create();
        $intruder = User::factory()->create();
        $comment = ArticleComment::create([
            'article_id' => $article->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'avatar_color' => '#CFE3F5',
            'body' => 'Beschermde reactie.',
        ]);

        $this->actingAs($intruder)
            ->delete("/blog-reacties/{$comment->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('article_comments', ['id' => $comment->id]);
    }
}
