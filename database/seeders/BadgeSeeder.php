<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

/**
 * Seeds the gentle, supportive milestone badges. Idempotent: keyed by the
 * badge's stable `key`, so re-running only refreshes name/emoji/description.
 */
class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['key' => 'welkom', 'emoji' => '🌸', 'name' => 'Nieuw lid', 'description' => 'Welkom in de community'],
            ['key' => 'eerste-bericht', 'emoji' => '💬', 'name' => 'Eerste bericht', 'description' => 'Je eerste bericht geplaatst'],
            ['key' => 'verhalenverteller', 'emoji' => '✍️', 'name' => 'Verhalenverteller', 'description' => 'Je eerste verhaal gedeeld'],
            ['key' => 'behulpzaam', 'emoji' => '💛', 'name' => 'Behulpzaam lid', 'description' => 'Je hebt een beste antwoord ontvangen'],
            ['key' => 'sociaal', 'emoji' => '👥', 'name' => 'Sociaal', 'description' => '10+ volgers'],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['key' => $badge['key']], $badge);
        }
    }
}
