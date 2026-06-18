<?php

namespace Database\Seeders;

use App\Models\ChecklistItem;
use Illuminate\Database\Seeder;

class ChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $uitzet = [
            'Kleding' => ['Rompertjes (0–3 mnd)', 'Rompertjes (3–6 mnd)', 'Pyjama\'s / slaapzakjes', 'Mutsjes & wantjes', 'Sokjes / schoentjes', 'Jasje / vestjes'],
            'Verzorging' => ['Luiers (newborn & maat 1)', 'Billendoekjes', 'Luiercrème / zinkzalf', 'Babybadje', 'Washandjes & handdoeken', 'Nagelknipper / vijl', 'Thermometer', 'Neusreiniger / aspirator'],
            'Voeding' => ['Flessen & spenen', 'Flessenwarmer', 'Borstkolf (indien nodig)', 'Voedingsbh / nursing pads', 'Sterilisator', 'Flesborstels'],
            'Slapen' => ['Ledikant / wieg', 'Matras + hoeslakens', 'Dekentje(s)', 'Mobiel', 'Nachtlampje'],
            'Reizen & vervoer' => ['Autostoeltje (groep 0/0+)', 'Kinderwagen / buggy', 'Luiertas', 'Draagzak / draagdoek', 'Regenscherm'],
            'Veiligheid' => ['Stopcontactbeschermers', 'Babyfoon', 'Veiligheidssloten kasten', 'EHBO-set voor baby'],
            'Speelgoed' => ['Rammelaar / knuffel', 'Bijtringen', 'Speelkleed / boxkleed', 'Boekjes / voelboekjes'],
        ];

        $vluchttas = [
            'Documenten' => ['Identiteitsbewijs', 'Zorgverzekeringspas', 'Zwangerschapskaart / dossier', 'Telefoonnummers verloskundige', 'Route naar ziekenhuis'],
            'Voor mama' => ['Comfortabele kleding', 'Nachtjapon / pyjama', 'Ondergoed (groot/wegwerp)', 'Warme sokken / sloffen', 'Voedingsbh', 'Badjas / vest', 'Outfit voor naar huis'],
            'Verzorging mama' => ['Toiletartikelen', 'Haarborstel / elastiekjes', 'Lippenbalsem', 'Handdoek / washandje', 'Kraamverband'],
            'Comfort mama' => ['Snacks & repen', 'Drinkfles', 'Voedingskussen', 'Oordopjes / oogmasker'],
            'Voor de baby' => ['Rompertjes (0–3 mnd)', 'Pyjama / slaapzakje', 'Mutsje & sokjes', 'Luiers (newborn)', 'Dekentje', 'Eerste-foto outfit'],
            'Overig' => ['Telefoon + oplader', 'Camera (optioneel)', 'Muziek / afspeellijst', 'Snacks voor partner'],
        ];

        // Activity checklist for the expecting father, grouped per trimester.
        $vader = [
            '1e trimester' => ['Eerste echo bijwonen', 'Samen voeding en supplementen bespreken', 'Zwangerschapsboeken of apps raadplegen', 'Vragenlijst maken voor de verloskundige'],
            '2e trimester' => ['Eerste bewegingen van de baby voelen', 'Samen babykamer inrichten', 'Aanstaande oudercursus plannen', 'Babynamenlijst maken / bespreken'],
            '3e trimester' => ['Vluchttas inpakken (voor mama en baby)', 'Bevallingsplan bespreken', 'Kraamzorg regelen en afspraken bijhouden', 'Samen ontspanningsoefeningen of yoga doen'],
        ];

        $position = 0;
        foreach (['uitzet' => $uitzet, 'vluchttas' => $vluchttas, 'vader' => $vader] as $type => $categories) {
            foreach ($categories as $category => $items) {
                foreach ($items as $label) {
                    ChecklistItem::updateOrCreate(
                        ['type' => $type, 'category' => $category, 'label' => $label],
                        ['position' => $position++]
                    );
                }
            }
        }
    }
}
