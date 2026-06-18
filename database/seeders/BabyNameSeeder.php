<?php

namespace Database\Seeders;

use App\Models\BabyName;
use Illuminate\Database\Seeder;

class BabyNameSeeder extends Seeder
{
    public function run(): void
    {
        $girls = ['Aimee', 'Amara', 'Amélie', 'Amira', 'Anna', 'Anne', 'Aria', 'Arwen', 'Aylin', 'Azura', 'Celine', 'Chloé', 'Elise', 'Elodie', 'Elin', 'Emma', 'Esmée', 'Eva', 'Evy', 'Faye', 'Fleur', 'Freya', 'Isabella', 'Isla', 'Jolie', 'Julia', 'Juna', 'Kiara', 'Kira', 'Liora', 'Livia', 'Liv', 'Lyra', 'Lotte', 'Luna', 'Mara', 'Mila', 'Mira', 'Milou', 'Noa', 'Noor', 'Nora', 'Nola', 'Nova', 'Olivia', 'Romy', 'Roos', 'Rosa', 'Rosalie', 'Saffira', 'Sara', 'Selene', 'Sienna', 'Sofie', 'Sophie', 'Tessa', 'Tess', 'Thalía', 'Violetta', 'Xanna', 'Zara', 'Zoë'];
        $boys = ['Alaric', 'Arlo', 'Arvid', 'Benjamin', 'Cas', 'Casper', 'Caspian', 'Daan', 'Damon', 'David', 'Dorian', 'Emil', 'Elias', 'Elijah', 'Ezra', 'Fabian', 'Felix', 'Finn', 'Ian', 'Jace', 'Jack', 'Julian', 'Kevin', 'Leonardo', 'Levi', 'Liam', 'Luca', 'Lucas', 'Lucian', 'Matteo', 'Mats', 'Max', 'Nathan', 'Niels', 'Noah', 'Oliver', 'Olivier', 'Orion', 'Quinn', 'Ruben', 'Samuel', 'Sem', 'Silas', 'Soren', 'Stef', 'Stefan', 'Stijn', 'Timo', 'Thomas', 'Thijs', 'Tristan'];
        $twins = ['Emma & Sophie', 'Noor & Luna', 'Mila & Elise', 'Julia & Zoë', 'Anna & Fleur', 'Amélie & Elodie', 'Isla & Aria', 'Faye & Nova', 'Lucas & Finn', 'Noah & Liam', 'Daan & Max', 'Julian & Levi', 'Thijs & Mats', 'Matteo & Luca', 'Emma & Lucas', 'Mila & Noah', 'Luna & Finn', 'Sophie & Daan', 'Elise & Levi', 'Nora & Nola', 'Lyra & Finn', 'Aria & Kai'];

        $rows = [];
        foreach ($girls as $n) {
            $rows[] = ['name' => $n, 'type' => 'meisjes'];
        }
        foreach ($boys as $n) {
            $rows[] = ['name' => $n, 'type' => 'jongens'];
        }
        foreach ($twins as $n) {
            $rows[] = ['name' => $n, 'type' => 'tweeling'];
        }

        foreach ($rows as $row) {
            BabyName::firstOrCreate(['name' => $row['name'], 'type' => $row['type']]);
        }
    }
}
