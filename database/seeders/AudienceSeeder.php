<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Audience;
use App\Models\CommunityGroup;
use App\Models\ForumCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AudienceSeeder extends Seeder
{
    public function run(): void
    {
        $audiences = [
            [
                'name' => 'Tienerouders',
                'emoji' => '🌷',
                'tagline' => 'Jong ouderschap, met alle steun die je verdient',
                'color_from' => '#FCE7EB', 'color_to' => '#F3F0FB',
                'g1' => '#F9C8D0', 'g2' => '#D3BCE6',
                'intro' => 'Jong moeder of vader worden brengt extra vragen mee — over school, geld en wat anderen vinden. Hier vind je steun, zonder oordeel.',
                'body' => "Op jonge leeftijd ouder worden is bijzonder én pittig. Misschien voel je je soms onzeker, of merk je oordelen van anderen. Weet dit: jouw liefde voor je kindje telt, niet je leeftijd.\n\nHier draait alles om steun van ouder tot ouder. Je vindt tips over school en opleiding combineren met een baby, informatie over regelingen en hulpinstanties, en vooral: andere jonge ouders die precies snappen wat jij doormaakt.\n\nJe hoeft het niet alleen te doen. Stel je vraag in de groep, deel je verhaal en ontdek dat je sterker bent dan je denkt. 💛",
                'tips' => [
                    'Combineer school en ouderschap: vraag je opleiding naar maatwerk en kinderopvangregelingen.',
                    'Ken je rechten: informeer bij de gemeente naar financiële ondersteuning en hulp.',
                    'Zoek peer-steun: andere jonge ouders begrijpen je situatie het best.',
                    'Wees lief voor jezelf — jong ouderschap vraagt veel, vier elke kleine overwinning.',
                ],
                'article' => [
                    'title' => 'Jong ouder, sterk ouder: jij kunt dit',
                    'excerpt' => 'Tienerouder zijn brengt extra uitdagingen mee. Over steun zoeken, je rechten kennen en geloven in jezelf.',
                    'body' => "Jong ouder worden was misschien niet gepland, maar dat maakt je liefde niet minder waard. Veel tienerouders voelen druk van buitenaf — laat dat je niet ontmoedigen.\n\nPraktisch: er is meer hulp dan je denkt. Scholen bieden vaak maatwerk, gemeenten hebben regelingen, en er zijn organisaties speciaal voor jonge ouders. Vraag ernaar; het is geen zwakte, het is slim.\n\nEn emotioneel: omring je met mensen die je steunen. In onze community vind je andere jonge ouders die weten hoe het is. Van ouder tot ouder, zonder oordeel.",
                ],
            ],
            [
                'name' => 'Grootouders',
                'emoji' => '👵',
                'tagline' => 'Een onmisbare schakel in het gezin',
                'color_from' => '#FBEFD8', 'color_to' => '#FCE7EB',
                'g1' => '#F6DDB0', 'g2' => '#E9C788',
                'intro' => 'Als oma of opa speel je een grote rol — van oppassen tot een luisterend oor. Hier vind je inspiratie voor jouw bijzondere band.',
                'body' => "Grootouders zijn goud waard. Je past op, je geeft wijze raad en je biedt een veilige plek voor je kleinkind én voor je eigen kind als ouder.\n\nTegelijk is opvoeden van nu soms anders dan vroeger: andere adviezen over slapen, voeding en veiligheid. Hier vind je hoe je meebeweegt met deze tijd, zonder je eigen ervaring te verliezen.\n\nWil je vaker oppassen? Bekijk ook onze oppas-pagina. En deel gerust je verhalen en vragen met andere grootouders in de community.",
                'tips' => [
                    'Stem met de ouders af: opvoedafspraken (slapen, eten, schermtijd) geven rust voor iedereen.',
                    'Opvoeden toen vs. nu: sommige adviezen zijn veranderd — blijf nieuwsgierig.',
                    'Jouw tijd is een cadeau: voorlezen, knutselen en samen koken versterken de band.',
                    'Pas je op? Bekijk de oppas-pagina om je rol vast te leggen.',
                ],
                'article' => [
                    'title' => 'Oma & opa van nu: opvoeden toen en nu',
                    'excerpt' => 'Veel opvoedadviezen zijn veranderd sinds jij kinderen kreeg. Een warme blik op meebewegen met deze tijd.',
                    'body' => "Als grootouder heb je een schat aan ervaring. Toch verandert er veel: baby's slapen nu op hun rug, voedingsadviezen zijn anders en autostoeltjes zien er compleet anders uit.\n\nDat betekent niet dat jouw ervaring niet telt — integendeel. Het mooiste is samen optrekken met de ouders: vraag hoe zij het willen, deel jouw wijsheid en vind samen een ritme.\n\nEn vergeet niet wat alleen jij kunt geven: tijd, geduld en onvoorwaardelijke liefde. Dat is van alle tijden.",
                ],
            ],
            [
                'name' => 'Adoptieouders',
                'emoji' => '🌍',
                'tagline' => 'Een gezin gevormd met het hart',
                'color_from' => '#E1EEFB', 'color_to' => '#EAF5EE',
                'g1' => '#CFE3F5', 'g2' => '#A9CCE8',
                'intro' => 'Adoptie is een bijzondere reis met eigen vragen rond hechting, afkomst en identiteit. Hier vind je herkenning en steun.',
                'body' => "Een gezin vormen via adoptie is prachtig en uniek. Het brengt ook eigen thema's mee: het adoptieproces, hechting, en later de gesprekken met je kind over zijn of haar afkomst.\n\nHechten kost soms tijd en geduld. Nabijheid, voorspelbaarheid en geduld helpen jullie band te laten groeien. Je staat er niet alleen voor — andere adoptieouders delen hier hun ervaringen.\n\nOok aandacht voor de achtergrond en cultuur van je kind hoort erbij. Hier vind je tips, verhalen en een community die begrijpt wat adoptie met zich meebrengt.",
                'tips' => [
                    'Hechting groeit met nabijheid, voorspelbaarheid en geduld — geef het tijd.',
                    'Praat open en op maat over adoptie, passend bij de leeftijd van je kind.',
                    'Geef ruimte aan de afkomst en cultuur van je kind.',
                    'Zoek lotgenoten: andere adoptieouders herkennen jouw vragen.',
                ],
                'article' => [
                    'title' => 'Hechten na adoptie: geduld, nabijheid en vertrouwen',
                    'excerpt' => 'Hechting na adoptie verloopt op zijn eigen tempo. Wat helpt om jullie band te laten groeien?',
                    'body' => "Na adoptie is hechten niet altijd vanzelfsprekend — voor kind én ouder. Je kindje moet leren vertrouwen, en jij leert je kindje kennen.\n\nWat helpt: voorspelbaarheid (vaste ritmes en rituelen), veel nabijheid en geduld. Kleine momenten van contact — samen lezen, knuffelen, oogcontact — bouwen langzaam vertrouwen op.\n\nWees mild voor jezelf als het tijd kost. Twijfels horen erbij. Deel ze met andere adoptieouders; herkenning lucht op en geeft houvast.",
                ],
            ],
            [
                'name' => 'Pleegouders',
                'emoji' => '🤲',
                'tagline' => 'Een veilige haven, hoe lang ook',
                'color_from' => '#E4F3E9', 'color_to' => '#EAF5EE',
                'g1' => '#B7E1C0', 'g2' => '#9AD3AC',
                'intro' => 'Pleegouders bieden een kind een veilig thuis — soms kort, soms lang. Hier vind je steun bij hechten, loslaten en samenwerken.',
                'body' => "Pleegouder zijn is een prachtige en bijzondere taak. Je geeft een kind een veilige plek, vaak in een onzekere periode van zijn of haar leven.\n\nDat vraagt veel: hechten aan een kind dat misschien weer vertrekt, samenwerken met instanties en biologische ouders, en opvoeden met oog voor wat een kind heeft meegemaakt (trauma-sensitief).\n\nJe hoeft dit niet alleen te dragen. Hier delen pleegouders hun ervaringen — over de mooie momenten én de moeilijke. Van ouder tot ouder, met begrip.",
                'tips' => [
                    'Hechten én kunnen loslaten: geef liefde nu, ook al is de toekomst onzeker.',
                    'Werk samen met instanties en (waar mogelijk) de biologische ouders.',
                    'Opvoeden met oog voor wat een kind meemaakte: voorspelbaarheid en veiligheid voorop.',
                    'Zorg ook voor jezelf — steun van andere pleegouders is onmisbaar.',
                ],
                'article' => [
                    'title' => 'Pleegouder zijn: hechten én kunnen loslaten',
                    'excerpt' => 'Een kind een veilig thuis geven, wetend dat het misschien weer vertrekt. Over deze bijzondere balans.',
                    'body' => "Pleegouderschap kent een unieke spanning: je geeft een kind je volle liefde, terwijl je weet dat het misschien terugkeert naar huis of verder gaat.\n\nDurf toch te hechten. Juist de veiligheid en warmte die jij biedt, helpen een kind groeien — hoe lang het ook bij je is. Tegelijk mag je je eigen verdriet bij een afscheid erkennen; dat hoort erbij.\n\nSamenwerken met hulpverlening en biologische ouders kan ingewikkeld zijn. Wees helder, blijf bij het belang van het kind, en zoek steun bij andere pleegouders die dit pad kennen.",
                ],
            ],
            [
                'name' => 'Alleenstaande ouders',
                'emoji' => '💪',
                'tagline' => 'Sterk in je eentje, samen in de community',
                'color_from' => '#FCE7EB', 'color_to' => '#E1EEFB',
                'g1' => '#F7A8B5', 'g2' => '#A9CCE8',
                'intro' => 'In je eentje de zorg dragen vraagt veel. Hier vind je praktische tips, herkenning en een community die je opvangt.',
                'body' => "Alleen voor je kind(eren) zorgen is knap — en soms zwaar. Alles komt op jou neer: de zorg, de beslissingen, het huishouden en het werk.\n\nHier draait het om praktische steun en herkenning. Tips voor balans en \"mij-tijd\", informatie over regelingen en hulp, en vooral andere alleenstaande ouders die snappen hoe jouw dagen eruitzien.\n\nVraag gerust om hulp — dat is geen zwakte maar wijsheid. En vier je eigen veerkracht; je doet het fantastisch. 💛",
                'tips' => [
                    'Bouw een steunnetwerk: familie, vrienden of andere ouders die kunnen bijspringen.',
                    'Plan bewust \"mij-tijd\", hoe klein ook — opladen mag.',
                    'Ken je rechten: informeer bij de gemeente naar regelingen en toeslagen.',
                    'Deel de mentale last: praat met lotgenoten, je hoeft niet alles alleen te dragen.',
                ],
                'article' => [
                    'title' => 'Alleenstaand ouder: sterk, maar niet alleen',
                    'excerpt' => 'In je eentje de zorg dragen is pittig. Over steun vragen, balans vinden en je eigen veerkracht vieren.',
                    'body' => "Als alleenstaande ouder doe je het werk van twee. Dat vraagt veel energie en organisatie. Het allerbelangrijkste: je hoeft het niet perfect te doen, en je hoeft het niet alleen te doen.\n\nDurf hulp te vragen — aan familie, vrienden of via de gemeente. Een steunnetwerk maakt het verschil. En plan momenten voor jezelf in; een uitgeruste ouder is een betere ouder.\n\nIn onze community vind je andere alleenstaande ouders. Herkenning, een luisterend oor en praktische tips: van ouder tot ouder.",
                ],
            ],
            [
                'name' => 'Co-ouders',
                'emoji' => '🤝',
                'tagline' => 'Samen ouder, ook na de scheiding',
                'color_from' => '#E1EEFB', 'color_to' => '#EAF5EE',
                'g1' => '#CFE3F5', 'g2' => '#B7E1C0',
                'intro' => 'Co-ouderschap vraagt om goede afspraken en communicatie. Hier vind je tips om het samen soepel en liefdevol te regelen.',
                'body' => "Na een scheiding samen ouder blijven: dat is co-ouderschap. Het mooiste is dat je kind bij jullie beiden thuis is — en dat vraagt om duidelijke afspraken en goede communicatie.\n\nHier vind je tips over een werkbaar ouderschapsplan, het afstemmen van regels tussen twee huizen, en hoe je conflicten weghoudt bij je kind.\n\nHet draait altijd om het belang van je kind. Met respect en overleg maak je co-ouderschap tot iets warms en stabiels. Andere co-ouders delen hier hun ervaringen.",
                'tips' => [
                    'Maak een helder ouderschapsplan en evalueer het regelmatig.',
                    'Stem basisregels af tussen beide huizen voor rust en voorspelbaarheid.',
                    'Houd je kind buiten conflicten — communiceer zakelijk en respectvol.',
                    'Gebruik een gedeelde agenda of app voor afspraken en overdracht.',
                ],
                'article' => [
                    'title' => 'Co-ouderschap: samen sterk voor je kind',
                    'excerpt' => 'Twee huizen, één team. Over goede afspraken, communicatie en het belang van je kind voorop.',
                    'body' => "Co-ouderschap werkt het best als jullie als team blijven functioneren — ook al zijn jullie geen partners meer. Je kind vaart wel bij rust, voorspelbaarheid en twee ouders die respectvol met elkaar omgaan.\n\nEen duidelijk ouderschapsplan helpt: wie doet wat, wanneer, en hoe stem je grote beslissingen af? Stem ook basisregels af tussen de twee huizen, zodat je kind weet waar het aan toe is.\n\nLukt communiceren niet altijd? Dat is menselijk. Houd je kind buiten discussies en zoek steun bij andere co-ouders die hetzelfde pad bewandelen.",
                ],
            ],
            [
                'name' => 'Regenboogouders',
                'emoji' => '🌈',
                'tagline' => 'Liefde maakt een gezin',
                'color_from' => '#F3F0FB', 'color_to' => '#FCE7EB',
                'g1' => '#E6D6F0', 'g2' => '#F9C8D0',
                'intro' => 'LHBTI+-ouders vormen hun gezin op hun eigen manier. Hier vind je herkenning, tips en een warme community zonder oordeel.',
                'body' => "Een regenbooggezin ontstaat vaak via een bewuste, soms lange route: donorconceptie, draagmoederschap, adoptie of pleegzorg. Dat brengt eigen vragen en mooie verhalen mee.\n\nHier vind je herkenning bij andere LHBTI+-ouders: over de weg naar ouderschap, het uitleggen van jullie gezinsvorm, en omgaan met reacties uit de omgeving.\n\nLiefde maakt een gezin. Hier mag jullie verhaal er helemaal zijn — gevierd en gesteund, van ouder tot ouder. 🌈",
                'tips' => [
                    'Verken je route naar ouderschap (donor, draagmoeder, adoptie, pleegzorg) goed.',
                    'Regel het juridisch goed: ouderschap, gezag en erkenning.',
                    'Praat open en positief over jullie gezinsvorm, passend bij je kind.',
                    'Zoek lotgenoten — herkenning bij andere regenboogouders geeft kracht.',
                ],
                'article' => [
                    'title' => 'Een regenboogezin vormen: jullie eigen weg',
                    'excerpt' => 'Van kinderwens tot gezin: LHBTI+-ouders gaan vaak een bewuste route. Over keuzes, recht en trots.',
                    'body' => "De weg naar ouderschap is voor regenboogouders vaak een bewuste reis, met keuzes rond donorconceptie, draagmoederschap, adoptie of pleegzorg. Elke route heeft eigen vragen — praktisch, emotioneel en juridisch.\n\nRegel het juridisch goed: ouderschap, gezag en erkenning verschillen per situatie. Laat je goed informeren zodat jullie gezin ook op papier stevig staat.\n\nEn geniet van jullie verhaal. Praat er open en trots over, passend bij de leeftijd van je kind. In onze community vind je andere regenboogouders die jullie pad herkennen.",
                ],
            ],
            [
                'name' => 'Ouders van een kindje met extra zorg',
                'emoji' => '🧡',
                'tagline' => 'Extra zorg, extra liefde',
                'color_from' => '#FBEFD8', 'color_to' => '#E4F3E9',
                'g1' => '#F6DDB0', 'g2' => '#9AD3AC',
                'intro' => 'Een kind met een beperking, ziekte of bijzondere zorgbehoefte vraagt extra. Hier vind je steun, kennis en lotgenoten.',
                'body' => "Zorgen voor een kindje met een beperking, chronische ziekte of bijzondere zorgbehoefte is intens en liefdevol. Je bent ouder én vaak ook zorgcoördinator, belangenbehartiger en specialist in je eigen kind.\n\nHier vind je herkenning bij ouders die weten wat ziekenhuisbezoeken, indicaties en zorgregelingen betekenen. Praktische tips, maar ook ruimte voor de emoties die erbij horen.\n\nVergeet jezelf niet: jouw veerkracht is kostbaar. Deel je verhaal, vraag om hulp en weet dat je hier begrepen wordt. 🧡",
                'tips' => [
                    'Houd een eigen dossier bij van diagnoses, afspraken en contacten.',
                    'Informeer naar zorgregelingen, pgb en respijtzorg (even op adem komen).',
                    'Zoek lotgenoten en patiëntenverenigingen — gedeelde kennis is goud.',
                    'Zorg ook voor jezelf en je relatie; mantelzorg vraagt veel.',
                ],
                'article' => [
                    'title' => 'Ouder van een kindje met extra zorg: jij kent je kind het best',
                    'excerpt' => 'Tussen ziekenhuis, indicaties en dagelijkse zorg. Over kennis bundelen, hulp regelen en goed voor jezelf zorgen.',
                    'body' => "Ouder zijn van een kind met extra zorg betekent vaak een dubbele rol: je geeft liefde én coördineert zorg. Dat is zwaar, en tegelijk groei je uit tot de grootste expert op het gebied van jouw kind.\n\nHoud overzicht: een eigen dossier met diagnoses, afspraken en contactpersonen helpt enorm. Informeer naar regelingen zoals een pgb en respijtzorg, zodat je af en toe kunt opladen.\n\nEn zorg voor jezelf — je kunt alleen goed zorgen als je zelf overeind blijft. Lotgenoten en patiëntenverenigingen bieden kennis én begrip. Je staat er niet alleen voor.",
                ],
            ],
        ];

        foreach ($audiences as $i => $a) {
            $slug = Str::slug($a['name']);

            $audience = Audience::updateOrCreate(['slug' => $slug], [
                'name' => $a['name'],
                'emoji' => $a['emoji'],
                'tagline' => $a['tagline'],
                'intro' => $a['intro'],
                'body' => $a['body'],
                'tips' => $a['tips'],
                'color_from' => $a['color_from'],
                'color_to' => $a['color_to'],
                'position' => $i,
            ]);

            CommunityGroup::updateOrCreate(['name' => $a['name']], [
                'audience_id' => $audience->id,
                'members' => 0,
                'color_from' => $a['g1'],
                'color_to' => $a['g2'],
            ]);

            ForumCategory::updateOrCreate(['slug' => $slug], [
                'audience_id' => $audience->id,
                'name' => $a['name'],
                'description' => $a['tagline'].'.',
                'emoji' => $a['emoji'],
                'color_from' => $a['color_from'],
                'color_to' => $a['color_to'],
                'position' => 20 + $i,
            ]);

            Article::updateOrCreate(['slug' => Str::slug($a['article']['title'])], [
                'audience_id' => $audience->id,
                'title' => $a['article']['title'],
                'category' => $a['name'],
                'emoji' => $a['emoji'],
                'color_from' => $a['color_from'],
                'color_to' => $a['color_to'],
                'reading_minutes' => 4,
                'excerpt' => $a['article']['excerpt'],
                'body' => $a['article']['body'],
                'author_name' => 'Stephanie van der Kooij',
                'published_at' => now()->subDays(4 - $i),
            ]);
        }
    }
}
