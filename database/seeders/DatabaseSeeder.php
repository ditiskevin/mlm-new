<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PregnancyWeekSeeder::class,
            BabyNameSeeder::class,
            ChecklistItemSeeder::class,
            CommunityGroupSeeder::class,
            RevealIdeaSeeder::class,
            ArticleSeeder::class,
            CommunityContentSeeder::class,
            ListingSeeder::class,
            BabysitterSeeder::class,
            ForumSeeder::class,
            AudienceSeeder::class,
        ]);
    }
}
