<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $lisa = User::where('email', 'lisa@example.com')->first();
        $naomi = User::where('email', 'naomi@example.com')->first();
        $stephanie = User::where('email', 'stephanie@mommylovesme.nl')->first();

        $emojis = ['Kleding' => '👕', 'Speelgoed' => '🧸', 'Babyspullen' => '🍼', 'Meubels' => '🪑', 'Boeken' => '📚', 'Buggy & vervoer' => '🛒', 'Overig' => '📦'];

        $listings = [
            ['user' => $lisa, 'title' => 'Maxi-Cosi autostoeltje (groep 0+)', 'category' => 'Buggy & vervoer', 'offer_type' => 'aanbod', 'price' => 45.00, 'condition' => 'zo goed als nieuw', 'location' => 'Utrecht', 'avatar_color' => '#F7B3C0', 'description' => 'Nauwelijks gebruikt Maxi-Cosi in nette staat, inclusief verkleiner. Niet-rokende, huisdiervrije woning. Ophalen in Utrecht.'],
            ['user' => $naomi, 'title' => 'Setje rompertjes maat 50/56', 'category' => 'Kleding', 'offer_type' => 'gratis', 'price' => null, 'condition' => 'gebruikt', 'location' => 'Amersfoort', 'avatar_color' => '#9AD3AC', 'description' => 'Doos met ± 15 rompertjes en pyjamaatjes maat 50/56. Gewassen en compleet. Gratis op te halen, mag ook naar het goede doel.'],
            ['user' => $stephanie, 'title' => 'Houten babybox + kleed', 'category' => 'Meubels', 'offer_type' => 'aanbod', 'price' => 60.00, 'condition' => 'gebruikt', 'location' => 'Nijkerk', 'avatar_color' => '#F28B82', 'description' => 'Stevige houten box met matras en boxkleed. Demonteerbaar, makkelijk te vervoeren. Wat gebruikssporen maar verder prima.'],
            ['user' => $lisa, 'title' => 'Te ruilen: stapelbare bouwblokken', 'category' => 'Speelgoed', 'offer_type' => 'ruil', 'price' => null, 'condition' => 'zo goed als nieuw', 'location' => 'Utrecht', 'avatar_color' => '#F7B3C0', 'description' => 'Grote set zachte bouwblokken. Wil graag ruilen voor een activity-tafel of muziekspeelgoed (0-2 jaar).'],
            ['user' => $naomi, 'title' => 'Voorleesboekjes (set van 8)', 'category' => 'Boeken', 'offer_type' => 'aanbod', 'price' => 10.00, 'condition' => 'gebruikt', 'location' => 'Amersfoort', 'avatar_color' => '#9AD3AC', 'description' => 'Acht kartonnen voelboekjes en voorleesboekjes. Stevig en compleet. Samen voor een klein prijsje.'],
            ['user' => $lisa, 'title' => 'Gevraagd: flessenwarmer', 'category' => 'Babyspullen', 'offer_type' => 'gevraagd', 'price' => null, 'condition' => null, 'location' => 'Utrecht e.o.', 'avatar_color' => '#F7B3C0', 'description' => 'Wie heeft er een flessenwarmer over? Mag een gebruikt exemplaar zijn. Kan in de regio Utrecht ophalen. Alvast dank! 💛'],
        ];

        foreach ($listings as $l) {
            Listing::updateOrCreate(['slug' => Str::slug($l['title'])], [
                'user_id' => $l['user']?->id,
                'title' => $l['title'],
                'category' => $l['category'],
                'offer_type' => $l['offer_type'],
                'price' => $l['price'],
                'condition' => $l['condition'],
                'location' => $l['location'],
                'description' => $l['description'],
                'author_name' => $l['user']?->name ?? 'MommyLovesMe',
                'avatar_color' => $l['avatar_color'],
                'emoji' => $emojis[$l['category']] ?? '📦',
            ]);
        }
    }
}
