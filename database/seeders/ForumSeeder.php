<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Zwangerschap', 'emoji' => '🤰', 'description' => 'Alles over de 9 mooiste maanden — van symptomen tot voorbereiding.', 'color_from' => '#FCE7EB', 'color_to' => '#F3F0FB'],
            ['name' => 'Eerste jaar', 'emoji' => '🍼', 'description' => 'Slapen, voeding, mijlpalen en het ritme van het eerste jaar.', 'color_from' => '#E4F3E9', 'color_to' => '#EAF5EE'],
            ['name' => 'Peuters & kleuters', 'emoji' => '🧒', 'description' => 'Dreumesen, peuterpuberteit, zindelijkheid en spelen.', 'color_from' => '#E1EEFB', 'color_to' => '#EAF5EE'],
            ['name' => 'Bijzondere kinderen', 'emoji' => '🌿', 'description' => 'Caprin1, schisis, autisme, NLD, ADHD — steun en herkenning.', 'color_from' => '#EEE6F6', 'color_to' => '#FCE7EB'],
            ['name' => 'Creatief met kids', 'emoji' => '🎨', 'description' => 'Knutselen, recepten, uitjes en speelideeën.', 'color_from' => '#FBEFD8', 'color_to' => '#FCE7EB'],
            ['name' => 'Mama\'s die ondernemen', 'emoji' => '💼', 'description' => 'Werk, ondernemen en balans combineren met het gezin.', 'color_from' => '#E6D6F0', 'color_to' => '#E1EEFB'],
        ];

        foreach ($categories as $i => $c) {
            ForumCategory::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                array_merge($c, ['position' => $i])
            );
        }

        $stephanie = User::where('email', 'stephanie@mommylovesme.nl')->first();
        $lisa = User::where('email', 'lisa@example.com')->first();
        $naomi = User::where('email', 'naomi@example.com')->first();

        $topics = [
            [
                'cat' => 'zwangerschap', 'user' => $lisa, 'color' => '#F7B3C0',
                'title' => 'Tips tegen misselijkheid in het eerste trimester?',
                'body' => "Ik zit in week 7 en de misselijkheid is heftig, vooral 's ochtends. Wat heeft bij jullie geholpen? Ik probeer al kleine maaltijden, maar alle tips zijn welkom! 💛",
                'replies' => [
                    ['user' => $naomi, 'color' => '#9AD3AC', 'body' => 'Gemberthee en \'s ochtends een beschuitje nog vóór het opstaan deden bij mij wonderen. Hou vol!'],
                    ['user' => $stephanie, 'color' => '#F28B82', 'body' => 'Kleine beetjes vaak eten en niet te lang met een lege maag. Bij mij hielp ook frisse lucht. Beterschap! 🤍'],
                ],
            ],
            [
                'cat' => 'eerste-jaar', 'user' => $naomi, 'color' => '#9AD3AC',
                'title' => 'Doorslapen — wanneer kwam het bij jullie?',
                'body' => 'Onze kleine van 7 maanden wordt nog 2x per nacht wakker. Ik weet dat het normaal is, maar ik ben benieuwd naar jullie ervaringen. Wanneer ging het bij jullie beter?',
                'replies' => [
                    ['user' => $lisa, 'color' => '#F7B3C0', 'body' => 'Bij ons rond de 9 maanden, en echt pas na het stoppen met de nachtvoeding. Ieder kindje is anders!'],
                ],
            ],
            [
                'cat' => 'bijzondere-kinderen', 'user' => $stephanie, 'color' => '#F28B82',
                'title' => 'Herkenning gezocht: leven met een zeldzame diagnose',
                'body' => 'Mijn jongste heeft het Caprin1-syndroom. Een zeldzame diagnose voelt vaak eenzaam. Ik wil hier een plek maken waar we ervaringen delen — over zorg, school, en gewoon de dagelijkse dingen. Wie sluit aan? 💛',
                'replies' => [],
            ],
        ];

        foreach ($topics as $t) {
            $category = ForumCategory::where('slug', $t['cat'])->first();
            if (! $category) {
                continue;
            }

            $topic = ForumTopic::firstOrCreate(
                ['slug' => Str::slug($t['title'])],
                [
                    'forum_category_id' => $category->id,
                    'user_id' => $t['user']?->id,
                    'author_name' => $t['user']?->name ?? 'MommyLovesMe',
                    'avatar_color' => $t['color'],
                    'title' => $t['title'],
                    'body' => $t['body'],
                    'last_activity_at' => now(),
                ]
            );

            foreach ($t['replies'] as $r) {
                $topic->replies()->firstOrCreate(
                    ['body' => $r['body']],
                    [
                        'user_id' => $r['user']?->id,
                        'author_name' => $r['user']?->name ?? 'MommyLovesMe',
                        'avatar_color' => $r['color'],
                    ]
                );
            }

            if ($topic->replies()->exists()) {
                $topic->update(['last_activity_at' => $topic->replies()->latest()->first()->created_at]);
            }
        }
    }
}
