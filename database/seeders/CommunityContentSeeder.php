<?php

namespace Database\Seeders;

use App\Models\CommunityGroup;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommunityContentSeeder extends Seeder
{
    public function run(): void
    {
        $stephanie = User::firstOrCreate(
            ['email' => 'stephanie@mommylovesme.nl'],
            [
                'name' => 'Stephanie van der Kooij',
                'password' => Hash::make('password'),
                'role_label' => 'Oprichter · moeder van 4 💛',
                'bio' => 'Mama van vier (12, 9, 6 & 4). Mijn jongste heeft het Caprin1-syndroom - haar reis startte deze plek.',
                'avatar_color' => '#F28B82',
            ]
        );
        $lisa = User::firstOrCreate(
            ['email' => 'lisa@example.com'],
            ['name' => 'Lisa', 'password' => Hash::make('password')]
        );
        $naomi = User::firstOrCreate(
            ['email' => 'naomi@example.com'],
            ['name' => 'Naomi', 'password' => Hash::make('password')]
        );

        // Founder is the site moderator.
        if (! $stephanie->is_admin) {
            $stephanie->is_admin = true;
            $stephanie->save();
        }

        $zwangerschap = CommunityGroup::where('name', 'Zwangerschap')->first();
        $eersteJaar = CommunityGroup::where('name', 'Eerste jaar')->first();

        // Stephanie follows all groups (founder)
        $stephanie->followedGroups()->sync(CommunityGroup::pluck('id'));

        $posts = [
            [
                'user' => $lisa,
                'group' => $zwangerschap,
                'avatar_color' => '#F7B3C0',
                'tag' => 'Mijlpaal',
                'body' => 'Week 20 vandaag - de 20-weken echo was zó bijzonder. We zagen haar gapen 🥹 Iemand tips tegen rugpijn die nu opkomt?',
                'base_likes' => 24,
                'created_at' => now()->subHours(3),
            ],
            [
                'user' => $stephanie,
                'group' => null,
                'avatar_color' => '#F28B82',
                'tag' => 'Thema van de maand',
                'body' => 'Nieuw deze maand in de community: het thema "rust in het ouderschap". Deel jullie favoriete avondroutine hieronder 💛',
                'base_likes' => 41,
                'created_at' => now()->subHours(6),
            ],
            [
                'user' => $naomi,
                'group' => $eersteJaar,
                'avatar_color' => '#9AD3AC',
                'tag' => 'Steun',
                'body' => 'Onze kleine sliep voor het eerst door! Voor wie het zwaar heeft: het komt goed, hou vol. Van ouder tot ouder ❤️',
                'base_likes' => 67,
                'created_at' => now()->subDay(),
            ],
        ];

        $created = [];
        foreach ($posts as $p) {
            $created[] = Post::firstOrCreate(
                ['body' => $p['body']],
                [
                    'user_id' => $p['user']->id,
                    'community_group_id' => $p['group']?->id,
                    'author_name' => $p['user']->name,
                    'avatar_color' => $p['avatar_color'],
                    'tag' => $p['tag'],
                    'base_likes' => $p['base_likes'],
                    'created_at' => $p['created_at'],
                    'updated_at' => $p['created_at'],
                ]
            );
        }

        // A couple of demo comments on the first post (Lisa's milestone)
        if ($created[0]->comments()->count() > 0) {
            return;
        }

        $created[0]->comments()->createMany([
            [
                'user_id' => $stephanie->id,
                'author_name' => $stephanie->name,
                'avatar_color' => '#F28B82',
                'body' => 'Wat bijzonder, gefeliciteerd! 🥰 Tegen rugpijn helpt een zwangerschapskussen en rustig wandelen vaak goed.',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'user_id' => $naomi->id,
                'author_name' => $naomi->name,
                'avatar_color' => '#9AD3AC',
                'body' => 'Zo mooi! Warmte (kruik of douche) deed bij mij wonderen. ❤️',
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
        ]);
    }
}
