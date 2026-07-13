<?php

namespace Database\Seeders;

use App\Models\Audience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeds the standard "template" content that the site needs to function:
 * the "Ik ben…" / "Voor wie" audiences, checklists, pregnancy weeks, baby
 * names, reveal ideas, forum categories, community groups and starter blog
 * articles. Every underlying seeder is idempotent (updateOrCreate / firstOrCreate).
 *
 * Demo content (example posts, marketplace listings, babysitter profiles) is
 * intentionally NOT included — run `php artisan db:seed` for that.
 *
 * Guard: on a database that already contains audiences this is skipped, so a
 * redeploy never overwrites content an admin has since edited. Set
 * SEED_FRESH=true to force a re-seed (re-syncs templates to their definitions).
 */
class TemplateSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Badges are pure reference data (idempotent) — always keep them in sync,
        // even on a DB that already has the rest of the templates.
        $this->call([BadgeSeeder::class]);

        $alreadySeeded = Audience::query()->exists();
        $force = filter_var(env('SEED_FRESH', false), FILTER_VALIDATE_BOOLEAN);

        if ($alreadySeeded && ! $force) {
            $this->command?->info('TemplateSeeder: templates already present — skipping (set SEED_FRESH=true to force a re-seed).');

            return;
        }

        $this->call([
            PregnancyWeekSeeder::class,
            BabyNameSeeder::class,
            ChecklistItemSeeder::class,
            CommunityGroupSeeder::class,
            RevealIdeaSeeder::class,
            ForumSeeder::class,
            ArticleSeeder::class,
            AudienceSeeder::class,
        ]);

        $this->command?->info('TemplateSeeder: standard templates loaded. 💛');
    }
}
