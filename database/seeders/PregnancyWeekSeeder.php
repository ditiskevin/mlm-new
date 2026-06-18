<?php

namespace Database\Seeders;

use App\Models\PregnancyWeek;
use Illuminate\Database\Seeder;

class PregnancyWeekSeeder extends Seeder
{
    public function run(): void
    {
        $weeks = [
            ['label' => '1–2', 'trimester' => 1, 'fruit' => 'Nog geen vrucht', 'emoji' => '🌱', 'size' => '-', 'baby' => 'Technisch nog niet zwanger; je lichaam bereidt zich voor op de ovulatie.', 'mom' => 'Hormonen stijgen licht, nog geen duidelijke symptomen.', 'tip' => 'Start met foliumzuur en gezonde voeding, geen alcohol of roken.'],
            ['label' => '3', 'trimester' => 1, 'fruit' => 'Speldenknop', 'emoji' => '📍', 'size' => '~0,1 mm', 'baby' => 'Bevruchting vindt plaats; de zygote deelt zich.', 'mom' => 'Mogelijk lichte innestelingskrampjes of spotting.', 'tip' => 'Vermijd stress en blijf goed gehydrateerd.'],
            ['label' => '4', 'trimester' => 1, 'fruit' => 'Maanzaad', 'emoji' => '•', 'size' => '~0,2 mm', 'baby' => 'Innesteling in de baarmoederwand; de placenta begint te vormen.', 'mom' => 'Vermoeidheid en gevoelige borsten.', 'tip' => 'Doe een zwangerschapstest bij uitblijven van je menstruatie.'],
            ['label' => '5', 'trimester' => 1, 'fruit' => 'Sesamzaadje', 'emoji' => '•', 'size' => '~1–2 mm', 'baby' => 'Hart en organen beginnen zich te vormen.', 'mom' => 'Misselijkheid en gespannen borsten.', 'tip' => 'Eet kleine maaltijden verspreid over de dag.'],
            ['label' => '6', 'trimester' => 1, 'fruit' => 'Linze', 'emoji' => '🫘', 'size' => '~4 mm', 'baby' => 'Het hartje begint te kloppen; hoofd en romp worden zichtbaar.', 'mom' => 'Misselijkheid, vermoeidheid en geurgevoeligheid.', 'milestone' => 'Eerste hartslag zichtbaar via echo', 'tip' => 'Neem rust en draag losse, comfortabele kleding.'],
            ['label' => '7', 'trimester' => 1, 'fruit' => 'Bosbes', 'emoji' => '🫐', 'size' => '~1 cm', 'baby' => 'Armpjes en beentjes ontwikkelen zich; organen groeien verder.', 'mom' => 'Borsten groeien; cravings of juist aversies.', 'tip' => 'Een comfortabele bh en gezonde snacks helpen.'],
            ['label' => '8', 'trimester' => 1, 'fruit' => 'Framboos', 'emoji' => '🫐', 'size' => '~1,5 cm', 'baby' => 'Gezichtskenmerken vormen zich; het embryo beweegt intern.', 'mom' => 'Buik iets boller; vermoeidheid blijft.', 'tip' => 'Begin met lichte beweging zoals wandelen.'],
            ['label' => '9', 'trimester' => 1, 'fruit' => 'Druif', 'emoji' => '🍇', 'size' => '~2–3 cm', 'baby' => 'Vingertjes en teentjes vormen zich; organen werken samen.', 'mom' => 'Constipatie, maagzuur en vermoeidheid.', 'tip' => 'Eet vezelrijk, kleine maaltijden en drink voldoende water.'],
            ['label' => '10', 'trimester' => 1, 'fruit' => 'Kers', 'emoji' => '🍒', 'size' => '~3 cm', 'baby' => 'De embryonale fase eindigt; nu een foetus, organen functioneren deels.', 'mom' => 'Misselijkheid kan pieken; hormonale schommelingen.', 'echo' => 'Eerste prenatale controle', 'tip' => 'Blijf foliumzuur nemen en rust voldoende.'],
            ['label' => '11', 'trimester' => 1, 'fruit' => 'Vijg', 'emoji' => '🟣', 'size' => '~4 cm', 'baby' => 'Hoofd groot t.o.v. lichaam; geslachtsdelen ontwikkelen zich.', 'mom' => 'Vermoeidheid neemt af; humeur wisselend.', 'tip' => 'Houd prenatale info bij met een zwangerschapsapp.'],
            ['label' => '12', 'trimester' => 1, 'fruit' => 'Pruim', 'emoji' => '🟣', 'size' => '~5–6 cm', 'baby' => 'Organen grotendeels gevormd; bewegingen mogelijk.', 'mom' => 'Buikje wordt zichtbaar; minder misselijkheid.', 'echo' => 'Echo: hartslag zichtbaar', 'tip' => 'Focus op ijzerrijke voeding. Inschrijven cursus kan nu.'],
            ['label' => '13', 'trimester' => 1, 'fruit' => 'Perzik', 'emoji' => '🍑', 'size' => '~7 cm · 23 g', 'baby' => 'Botjes verharden; de foetus beweegt actief.', 'mom' => 'Energieker en minder misselijk.', 'tip' => 'Houd een zwangerschapsdagboek bij; overweeg prenatale yoga.'],
            ['label' => '14', 'trimester' => 2, 'fruit' => 'Citroen', 'emoji' => '🍋', 'size' => '~8 cm · 43 g', 'baby' => 'Hoofd en lichaam meer in verhouding; het gezichtje krijgt vorm.', 'mom' => 'Energieker, minder misselijk.', 'echo' => 'NIPT / combinatietest mogelijk', 'tip' => 'Start met zwangerschapsyoga of pilates.'],
            ['label' => '15', 'trimester' => 2, 'fruit' => 'Appel', 'emoji' => '🍎', 'size' => '~10 cm · 70 g', 'baby' => 'Spieren en botten ontwikkelen; bewegingen complexer.', 'mom' => 'Buik groeit; kleding kan strakker zitten.', 'tip' => 'Oriënteer je op bevallingsvoorbereiding.'],
            ['label' => '16', 'trimester' => 2, 'fruit' => 'Avocado', 'emoji' => '🥑', 'size' => '~11 cm · 100 g', 'baby' => 'Skelet en spieren sterker; gezichtsuitdrukkingen mogelijk.', 'mom' => 'Buik meer zichtbaar; energie neemt toe.', 'milestone' => 'Eerste schopjes (quickening) mogelijk voelbaar', 'tip' => 'Ideale tijd voor yoga, pilates of ademhalingsoefeningen.'],
            ['label' => '17', 'trimester' => 2, 'fruit' => 'Papaya', 'emoji' => '🟠', 'size' => '~12 cm · 140 g', 'baby' => 'Oortjes op hun plek; het gehoor ontwikkelt zich.', 'mom' => 'Gewichtstoename merkbaar; rugpijn mogelijk.', 'milestone' => 'Partner kan bewegingen soms voelen', 'tip' => 'Start eventueel een partnercursus of babyverzorging.'],
            ['label' => '18', 'trimester' => 2, 'fruit' => 'Ui', 'emoji' => '🧅', 'size' => '~14 cm · 190 g', 'baby' => 'Het skelet verkalkt; de wervelkolom wordt sterker.', 'mom' => 'Buik groeit; huid kan striemen ontwikkelen.', 'echo' => '20-weken echo voorbereiden', 'tip' => 'Oefen bevallingstechnieken samen met je partner.'],
            ['label' => '19', 'trimester' => 2, 'fruit' => 'Mango', 'emoji' => '🥭', 'size' => '~15 cm · 240 g', 'baby' => 'Niertjes maken urine; de foetus begint te slikken.', 'mom' => 'Buikronding duidelijker; zwangerschapskleding nodig.', 'tip' => 'EHBO voor baby of borstvoedingsworkshops starten.'],
            ['label' => '20', 'trimester' => 2, 'fruit' => 'Banaan', 'emoji' => '🍌', 'size' => '~16 cm · 300 g', 'baby' => 'Slaap-waakritme zichtbaar; reflexen ontwikkelen.', 'mom' => 'Halverwege! Energie wisselend.', 'echo' => '20-weken echo: structurele controle', 'tip' => 'Begin met moodboards voor de babykamer.'],
            ['label' => '21', 'trimester' => 2, 'fruit' => 'Wortel', 'emoji' => '🥕', 'size' => '~18 cm · 360 g', 'baby' => 'Skelet sterker; spieren groeien; huid bedekt met lanugo.', 'mom' => 'Rug en gewrichten gevoeliger.', 'tip' => 'Oefen verder met ademhalingstechnieken.'],
            ['label' => '22', 'trimester' => 2, 'fruit' => 'Paprika', 'emoji' => '🫑', 'size' => '~19 cm · 430 g', 'baby' => 'Het gehoor werkt; geluiden van buiten zijn waarneembaar.', 'mom' => 'Buik groeit; rugpijn mogelijk.', 'tip' => 'Praat en zing tegen je buik - je kindje hoort je!'],
            ['label' => '23', 'trimester' => 2, 'fruit' => 'Mango', 'emoji' => '🥭', 'size' => '~20 cm · 500 g', 'baby' => 'Longetjes ontwikkelen; de huid is nog dun.', 'mom' => 'Vermoeidheid; soms kortademig.', 'tip' => 'Begin met meubels uitkiezen voor de babykamer.'],
            ['label' => '24', 'trimester' => 2, 'fruit' => 'Kiwi', 'emoji' => '🥝', 'size' => '~21 cm · 600 g', 'baby' => 'Huid bedekt met vernix; spieren sterker.', 'mom' => 'Gewichtstoename zichtbaar; huid verandert.', 'tip' => 'Vanaf nu kun je de babykamer gaan inrichten.'],
            ['label' => '25', 'trimester' => 2, 'fruit' => 'Aubergine', 'emoji' => '🍆', 'size' => '~22 cm · 660 g', 'baby' => 'Reageert op aanraking; spieren worden sterk.', 'mom' => 'Buik groter; meer vermoeidheid.', 'tip' => 'Herhaal ademhalings- en persoefeningen.'],
            ['label' => '26', 'trimester' => 2, 'fruit' => 'Komkommer', 'emoji' => '🥒', 'size' => '~23 cm · 760 g', 'baby' => 'Oogjes openen en sluiten; huid minder transparant.', 'mom' => 'Benen kunnen opzwellen; slaap soms lastig.', 'tip' => 'Oefen bevallingsposities; partner leert ondersteunen.'],
            ['label' => '27', 'trimester' => 2, 'fruit' => 'Bloemkool', 'emoji' => '🥦', 'size' => '~24 cm · 875 g', 'baby' => 'Longen groeien; gewichtstoename versnelt.', 'mom' => 'Buik zwaar; rugpijn en vermoeidheid.', 'tip' => 'Start intensievere bevallingsvoorbereiding.'],
            ['label' => '28', 'trimester' => 3, 'fruit' => 'Aubergine', 'emoji' => '🍆', 'size' => '~37 cm · 1 kg', 'baby' => 'Hersenen ontwikkelen snel; oogleden openen en sluiten.', 'mom' => 'Buik zwaar; rugpijn en vermoeidheid.', 'echo' => 'Glucosetest mogelijk', 'milestone' => 'Krachtige, duidelijk voelbare bewegingen', 'tip' => 'Pak alvast je vluchttas in.'],
            ['label' => '29', 'trimester' => 3, 'fruit' => 'Pompoen', 'emoji' => '🎃', 'size' => '~38 cm · 1,1 kg', 'baby' => 'Longen ontwikkelen verder; spieren sterker.', 'mom' => 'Mogelijk slapeloosheid en kortademigheid.', 'tip' => 'Oefen bevallingstechniek; laatste praktische lessen.'],
            ['label' => '30', 'trimester' => 3, 'fruit' => 'Biet', 'emoji' => '🔴', 'size' => '~39 cm · 1,3 kg', 'baby' => 'Huid wordt gladder door vetopslag; gewicht neemt toe.', 'mom' => 'Kortademigheid; rug- en bekkenpijn.', 'tip' => 'Oefen ontspanningstechnieken.'],
            ['label' => '31', 'trimester' => 3, 'fruit' => 'Pompoen', 'emoji' => '🎃', 'size' => '~41 cm · 1,5 kg', 'baby' => 'Longen bijna ontwikkeld; kan het hoofdje bewegen.', 'mom' => 'Moeilijk slapen; bandenpijn.', 'tip' => 'Betrek je partner bij de oefeningen.'],
            ['label' => '32', 'trimester' => 3, 'fruit' => 'Honingmeloen', 'emoji' => '🍈', 'size' => '~42 cm · 1,7 kg', 'baby' => 'Vetophoping maakt de huid glad; hersenactiviteit neemt toe.', 'mom' => 'Vermoeidheid neemt toe; bekkenpijn.', 'tip' => 'Herhaal ademhalingstechnieken voor het ziekenhuis.'],
            ['label' => '33', 'trimester' => 3, 'fruit' => 'Pompoen', 'emoji' => '🎃', 'size' => '~43 cm · 1,9 kg', 'baby' => 'Draait meestal met het hoofdje naar beneden.', 'mom' => 'Buik zwaar; kortademig; slapeloosheid.', 'tip' => 'Neem de ziekenhuisroute door; rond de babykamer af.'],
            ['label' => '34', 'trimester' => 3, 'fruit' => 'Meloen', 'emoji' => '🍈', 'size' => '~44 cm · 2,1 kg', 'baby' => 'Gewichtstoename versnelt; minder ruimte om te bewegen.', 'mom' => 'Buik zwaar; bekken- en rugpijn.', 'tip' => 'Oefen persen en ontspanning; herzie je bevalplan.'],
            ['label' => '35', 'trimester' => 3, 'fruit' => 'Watermeloen', 'emoji' => '🍉', 'size' => '~45 cm · 2,3 kg', 'baby' => 'Longen bijna klaar; lanugo verdwijnt grotendeels.', 'mom' => 'Moeite met bewegen; frequent plassen.', 'tip' => 'Laatste praktische tips voor de opvang na de geboorte.'],
            ['label' => '36', 'trimester' => 3, 'fruit' => 'Pompoen', 'emoji' => '🎃', 'size' => '~46 cm · 2,5 kg', 'baby' => 'Vrijwel volledig ontwikkeld; klaar voor de geboorte.', 'mom' => 'Kortademig; slapeloosheid; oefenweeën mogelijk.', 'tip' => 'De babykamer mag nu zo goed als klaar zijn.'],
            ['label' => '37', 'trimester' => 3, 'fruit' => 'Pompoen', 'emoji' => '🎃', 'size' => '~47 cm · 2,7 kg', 'baby' => 'Volwassen genoeg voor geboorte; bewegingen beperkt door ruimte.', 'mom' => 'Moeilijk slapen; oefenweeën; rug- en bekkenpijn.', 'tip' => 'Oefen de bevalling volledig; bereid je partner voor.'],
            ['label' => '38', 'trimester' => 3, 'fruit' => 'Meloen', 'emoji' => '🍈', 'size' => '~48 cm · 3 kg', 'baby' => 'Huid roze en glad; het hoofdje is vaak ingedaald.', 'mom' => 'Buik zwaar; kortademig; veel plassen.', 'tip' => 'Laatste voorbereiding; neem de route nog eens door.'],
            ['label' => '39', 'trimester' => 3, 'fruit' => 'Meloen', 'emoji' => '🍈', 'size' => '~49 cm · 3,2 kg', 'baby' => 'Volledig ontwikkeld; bereidt zich voor op de geboorte.', 'mom' => 'Oefenweeën, vermoeidheid en ongemak.', 'tip' => 'Ontspanning en ademhalingsoefeningen.'],
            ['label' => '40', 'trimester' => 3, 'fruit' => 'Pompoen', 'emoji' => '🎃', 'size' => '~50 cm · 3–4 kg', 'baby' => 'Volgroeid en klaar om geboren te worden!', 'mom' => 'Klaar voor de bevalling; mogelijk ongemak en oefenweeën.', 'milestone' => 'Bewegingen voelbaar tot de geboorte', 'tip' => 'Volg je bevalplan. Bij regelmatige weeën of gebroken vliezen: bel je verloskundige. 💛'],
        ];

        // Extra per-week details: [course_tip, warning, nursery] keyed by week label.
        $extra = [
            '1–2' => ['Nog te vroeg; focus op basisinformatie lezen en een gezonde levensstijl.', null, null],
            '3' => ['Informeer je alvast over cursussen (zwangerschapsyoga, EHBO, bevallingsvoorbereiding).', 'Hevige pijn of bloeding', null],
            '4' => ['Nog geen actieve deelname nodig; oriënteer op trimester 2-cursussen.', 'Zeer hevige bloeding of ernstige krampen', null],
            '5' => ['Cursussen vergelijken en eventueel inschrijven voor start in week 12–14.', 'Extreme misselijkheid', null],
            '6' => ['Nog wachten tot trimester 2.', 'Sterke pijn, hevige bloeding', null],
            '7' => ['Nog wachten tot week 12–14.', 'Bloeding, hevige krampen', null],
            '8' => ['Oriëntatie op cursussen; inschrijven mogelijk vanaf week 12.', 'Ernstige pijn, koorts', null],
            '9' => ['Cursusplanning bekijken; start vaak vanaf week 14.', 'Bloeding, extreme pijn', null],
            '10' => ['Oriëntatie en inschrijven voor trimester 2-cursussen.', 'Overmatig braken, dehydratie', null],
            '11' => ['Inschrijven voor zwangerschapscursus vanaf week 12–14.', 'Bloeding, pijn', null],
            '12' => ['Inschrijving voor actieve deelname mogelijk; voorbereiding op trimester 2.', 'Ernstige pijn, koorts', null],
            '13' => ['Starten met cursusplanning; zwangerschapsyoga of ademhaling mogelijk vanaf week 14.', 'Bloeding, hevige buikpijn', null],
            '14' => ['Start met zwangerschapsyoga of pilates; cursusbegeleiding voor partner kan ook.', 'Bloeding, sterke buikpijn', null],
            '15' => ['Oriëntatie op bevallingsvoorbereiding; cursusdeelname vanaf week 16–18.', 'Onverklaarbare pijn, koorts', null],
            '16' => ['Ideale periode om te starten met zwangerschapsyoga, pilates of ademhalingsoefeningen.', 'Sterke krampen of bloeding', null],
            '17' => ['Partnercursus, borstvoeding en babyverzorging kan starten.', 'Hevige rugpijn of bloedverlies', null],
            '18' => ['Bevallingstechniek oefenen; partner leert ondersteunende technieken.', 'Plotselinge vochtophoping, ernstige hoofdpijn', null],
            '19' => ['EHBO voor baby starten; borstvoeding-workshops.', 'Aanhoudende krampen of bloedverlies', null],
            '20' => ['Bevallingstechniek, ademhalingsoefeningen, partner leren masseren.', 'Bloeding, koorts', 'Oriënteren, moodboards maken, lijst met spullen beginnen.'],
            '21' => ['Verdere ademhalingstechnieken oefenen; voorbereiding op de bevalling.', 'Plotselinge hevige buikpijn', null],
            '22' => ['Partnercursus; borstvoeding en verzorging oefenen.', 'Hevige bloeding', null],
            '23' => ['Bevallingstechniek herhalen; EHBO voor baby.', 'Koorts, plotselinge zwelling', 'Begin met meubels uitkiezen, kleuren en thema bepalen.'],
            '24' => ['Zwangerschapszwemmen kan starten; ontspanningsoefeningen.', 'Symptomen van pre-eclampsie', 'Vanaf week 24–28 kun je starten met inrichten en spullen aanschaffen.'],
            '25' => ['Herhaling van ademhaling en persoefeningen.', 'Sterke buikpijn', null],
            '26' => ['Oefenen van bevallingsposities; partner leert ondersteunen.', 'Bloeding, hevige hoofdpijn', null],
            '27' => ['Intensieve bevallingsvoorbereiding, ademhalingstechnieken, persoefeningen.', 'Vochtophoping, bloeddrukproblemen', null],
            '28' => ['Intensieve bevallingsvoorbereiding, partnercursus, ademhaling en persen oefenen.', 'Plotselinge vochtophoping, hevige hoofdpijn', null],
            '29' => ['Bevallingstechniek oefenen; laatste praktische lessen voor partner en moeder.', 'Sterke buikpijn, bloedverlies', null],
            '30' => ['Oefen posities voor de bevalling; ontspanningstechnieken.', 'Pre-eclampsie, hevige rugpijn', null],
            '31' => ['Bevalling herhalen; partner betrekken bij de oefeningen.', 'Bloedverlies, krampen', null],
            '32' => ['Herhaal ademhalingstechnieken; laatste praktische tips voor het ziekenhuis.', 'Pre-eclampsie, hevige hoofdpijn, gezwollen handen/voeten', null],
            '33' => ['Laatste praktische trainingen; partnercursus; ziekenhuisroute doornemen.', 'Sterke rugpijn, bloeding', 'Afronden, kledingsets sorteren, laatste decoratie.'],
            '34' => ['Oefenen van persen en ontspanning; bevallingsplan herzien.', 'Plotselinge hevige buikpijn of bloedverlies', 'Afronden, kledingsets sorteren, laatste decoratie.'],
            '35' => ['Partner oefenen; laatste praktische tips voor de opvang na de geboorte.', 'Sterke, regelmatige weeën vóór 37 weken', 'Afronden, kledingsets sorteren, laatste decoratie.'],
            '36' => ['Bevallingsoefeningen herhalen; laatste ontspanningstechnieken.', 'Hevige, regelmatige weeën, bloedverlies', 'De babykamer mag nu zo goed als klaar zijn.'],
            '37' => ['Bevalling volledig oefenen; partner voorbereiden.', 'Sterke en regelmatige weeën', null],
            '38' => ['Laatste praktische voorbereiding; route naar het ziekenhuis doornemen.', 'Regelmatige, hevige weeën', null],
            '39' => ['Laatste ontspanningstechnieken en ademhalingsoefeningen.', 'Regelmatige, hevige weeën; vliezen breken', null],
            '40' => ['Ontspanning, ademhaling, bevallingsplan volgen.', 'Start van de bevalling: regelmatige weeën, vliezen breken, bloeding', null],
        ];

        foreach ($weeks as $i => $week) {
            [$courseTip, $warning, $nursery] = $extra[$week['label']] ?? [null, null, null];
            PregnancyWeek::updateOrCreate(['position' => $i], array_merge($week, [
                'course_tip' => $courseTip,
                'warning' => $warning,
                'nursery' => $nursery,
            ]));
        }
    }
}
