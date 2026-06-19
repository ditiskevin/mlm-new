<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Audience;
use App\Models\Babysitter;
use App\Models\BabyName;
use App\Models\ChecklistItem;
use App\Models\CommunityGroup;
use App\Models\ForumCategory;
use App\Models\Listing;
use App\Models\Post;
use App\Models\PregnancyWeek;
use App\Models\User;
use Database\Seeders\TemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads_the_standard_template_content(): void
    {
        $this->seed(TemplateSeeder::class);

        $this->assertGreaterThan(0, Audience::count(), '"Voor wie" / "Ik ben" audiences should be seeded');
        $this->assertGreaterThan(0, ChecklistItem::count());
        $this->assertGreaterThan(0, PregnancyWeek::count());
        $this->assertGreaterThan(0, BabyName::count());
        $this->assertGreaterThan(0, ForumCategory::count());
        $this->assertGreaterThan(0, CommunityGroup::count());
        $this->assertGreaterThan(0, Article::count());

        // Seeded audiences become the "Ik ben…" / "Voor wie" options. (We assert
        // on the table rather than User::parentTypeMap(), which is statically
        // memoised per process and would be stale across tests.)
        $this->assertTrue(Audience::whereNotNull('slug')->exists());
    }

    public function test_it_does_not_seed_demo_accounts_or_user_content(): void
    {
        $this->seed(TemplateSeeder::class);

        $this->assertSame(0, User::count(), 'No demo user accounts should be created');
        $this->assertSame(0, Post::count(), 'No demo community posts');
        $this->assertSame(0, Listing::count(), 'No demo marketplace listings');
        $this->assertSame(0, Babysitter::count(), 'No demo babysitter profiles');
    }

    public function test_it_is_idempotent_and_skips_when_already_seeded(): void
    {
        $this->seed(TemplateSeeder::class);
        $audiences = Audience::count();
        $articles = Article::count();

        // Second run should be a no-op (guard skips because audiences exist).
        $this->seed(TemplateSeeder::class);

        $this->assertSame($audiences, Audience::count());
        $this->assertSame($articles, Article::count());
    }

    public function test_seed_fresh_forces_a_resync_without_duplicating(): void
    {
        $this->seed(TemplateSeeder::class);
        $audiences = Audience::count();

        putenv('SEED_FRESH=true');
        $_ENV['SEED_FRESH'] = 'true';
        try {
            $this->seed(TemplateSeeder::class);
        } finally {
            putenv('SEED_FRESH');
            unset($_ENV['SEED_FRESH']);
        }

        // updateOrCreate keeps it idempotent even when forced.
        $this->assertSame($audiences, Audience::count());
    }
}
