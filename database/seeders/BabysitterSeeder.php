<?php

namespace Database\Seeders;

use App\Models\Babysitter;
use App\Models\User;
use Illuminate\Database\Seeder;

class BabysitterSeeder extends Seeder
{
    public function run(): void
    {
        $naomi = User::where('email', 'naomi@example.com')->first();

        $sitters = [
            ['user' => $naomi, 'name' => 'Naomi', 'age' => 28, 'location' => 'Amersfoort', 'hourly_rate' => 9.00, 'experience_years' => 6, 'availability' => 'Avonden & weekenden', 'has_vog' => true, 'avatar_color' => '#9AD3AC', 'bio' => 'Moeder van één en ervaren oppas. Ik vind het heerlijk om met kinderen te knutselen en voor te lezen. Ervaring met baby\'s en peuters, ook met extra zorg. In het bezit van een geldige VOG en kinder-EHBO.'],
            ['user' => null, 'name' => 'Sanne', 'age' => 21, 'location' => 'Utrecht', 'hourly_rate' => 8.50, 'experience_years' => 3, 'availability' => 'Doordeweeks overdag', 'has_vog' => true, 'avatar_color' => '#F7B3C0', 'bio' => 'Pedagogiek-studente die graag oppast voor wat extra inkomen. Geduldig, creatief en betrouwbaar. Beschikbaar overdag tussen colleges door.'],
            ['user' => null, 'name' => 'Iris', 'age' => 34, 'location' => 'Nijkerk', 'hourly_rate' => 10.00, 'experience_years' => 10, 'availability' => 'Flexibel, in overleg', 'has_vog' => true, 'avatar_color' => '#A9CCE8', 'bio' => 'Gediplomeerd kinderverzorgende met jarenlange ervaring op een kinderdagverblijf. Rustig, warm en gestructureerd. Ervaring met kinderen met een beperking.'],
            ['user' => null, 'name' => 'Yara', 'age' => 19, 'location' => 'Amersfoort', 'hourly_rate' => 7.50, 'experience_years' => 2, 'availability' => 'Weekenden', 'has_vog' => false, 'avatar_color' => '#E9C788', 'bio' => 'Enthousiaste oppas die houdt van buitenspelen en spelletjes. Oppaservaring in de buurt en bij familie. VOG in aanvraag.'],
        ];

        foreach ($sitters as $s) {
            Babysitter::updateOrCreate(['name' => $s['name'], 'location' => $s['location']], [
                'user_id' => $s['user']?->id,
                'name' => $s['name'],
                'age' => $s['age'],
                'location' => $s['location'],
                'hourly_rate' => $s['hourly_rate'],
                'experience_years' => $s['experience_years'],
                'availability' => $s['availability'],
                'has_vog' => $s['has_vog'],
                'bio' => $s['bio'],
                'avatar_color' => $s['avatar_color'],
            ]);
        }
    }
}
