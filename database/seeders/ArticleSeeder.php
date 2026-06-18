<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Hoera, zwanger! Wat nu?',
                'category' => 'Zwangerschap',
                'emoji' => '🤰',
                'color_from' => '#FCE7EB',
                'color_to' => '#F3F0FB',
                'reading_minutes' => 4,
                'excerpt' => 'Dolblij én een beetje onzeker? Helemaal normaal. Een zachte start voor alles wat er nu op je afkomt.',
                'body' => "Gefeliciteerd, je bent zwanger! Misschien voel je je dolblij, maar ook een beetje onzeker. \"Wat gaat er nu gebeuren? Hoe gaan we dit samen doen? Hoe vertel ik het aan familie en vrienden?\" Het zijn vragen die veel aanstaande ouders bezighouden, en dat is helemaal normaal.\n\nTijdens je zwangerschap verandert er van alles. Je lichaam maakt ruimte voor een nieuw leven, emoties kunnen alle kanten opgaan en ondertussen groeit er een klein wondertje in je buik, week na week.\n\nGelukkig hoef je het niet allemaal zelf uit te zoeken. In onze zwangerschapskalender ontdek je precies wat er elke week gebeurt: wanneer het hartje gaat kloppen, wanneer je kindje begint te bewegen en welke veranderingen jij zelf merkt. Zo voel je je een stukje zekerder en beter voorbereid op deze bijzondere tijd.",
            ],
            [
                'title' => 'Leven met het Caprin1-syndroom',
                'category' => 'Caprin1',
                'emoji' => '🌿',
                'color_from' => '#E4F3E9',
                'color_to' => '#EAF5EE',
                'reading_minutes' => 6,
                'excerpt' => 'Een zeldzame diagnose verandert je wereld. Over zoeken, vinden en vooral: er niet alleen voor staan.',
                'body' => "Het Caprin1-syndroom is zeldzaam, en juist daardoor voelt een diagnose vaak eenzaam. Er zijn weinig artsen die het kennen en weinig ouders om je verhaal mee te delen. Toch ben je niet alleen.\n\nIeder kind met Caprin1 is uniek. De ene heeft vooral uitdagingen met spraak en taal, de ander met motoriek of prikkelverwerking. Wat ouders delen is de zoektocht: naar de juiste begeleiding, naar erkenning en naar momenten van rust.\n\nMijn jongste dochter heeft Caprin1 en werd geboren met een gedeeltelijke schisis. Haar reis startte deze plek. Op MommyLovesMe willen we ervaringen bundelen, zodat je niet bij nul hoeft te beginnen. Deel je verhaal in de community - herkenning is soms het mooiste medicijn.",
            ],
            [
                'title' => 'Schisis: van diagnose tot eerste lachje',
                'category' => 'Schisis',
                'emoji' => '🩷',
                'color_from' => '#FCE7EB',
                'color_to' => '#FBEFD8',
                'reading_minutes' => 5,
                'excerpt' => 'Een schisis (hazenlip/gespleten gehemelte) roept veel vragen op. Wat kun je verwachten rond voeding, operaties en zorg?',
                'body' => "Wanneer je hoort dat je kindje een schisis heeft, komen er veel vragen. Hoe gaat de voeding? Wanneer volgt de operatie? En hoe ziet de zorg eruit?\n\nEen schisis wordt in Nederland behandeld door een gespecialiseerd team: een plastisch chirurg, kaakchirurg, logopedist, kno-arts en meer. Vaak volgt de eerste operatie rond de derde tot zesde maand. Veel ouders zijn verrast hoe goed kinderen zich herstellen.\n\nVoeding kan in het begin lastig zijn. Speciale spenen en flessen helpen, en een lactatiekundige of verpleegkundige van het schisisteam denkt met je mee. Geef jezelf de tijd: jullie vinden samen een ritme. En vergeet niet - dat eerste scheve lachje is er eentje om nooit te vergeten.",
            ],
            [
                'title' => 'Autisme bij jonge kinderen herkennen',
                'category' => 'Autisme',
                'emoji' => '🧩',
                'color_from' => '#E1EEFB',
                'color_to' => '#EAF5EE',
                'reading_minutes' => 5,
                'excerpt' => 'Wat zijn vroege signalen, en wat helpt? Een liefdevolle blik op autisme zonder oordeel.',
                'body' => "Autisme uit zich bij ieder kind anders. Toch zijn er signalen die vaak vroeg opvallen: minder oogcontact, intense interesses, gevoeligheid voor prikkels of moeite met veranderingen in routine.\n\nEen vermoeden betekent niet meteen een diagnose. Bespreek je zorgen met het consultatiebureau of de huisarts; zij kunnen je verwijzen voor onderzoek. Hoe eerder er begrip is, hoe eerder je kindje passende begeleiding krijgt.\n\nHet belangrijkste: jouw kind is geen diagnose. Structuur, voorspelbaarheid en een veilige omgeving doen wonderen. En jij, als ouder, kent je kind het beste. Vertrouw op dat gevoel.",
            ],
            [
                'title' => 'NLD: wanneer woorden makkelijker zijn dan de wereld',
                'category' => 'NLD',
                'emoji' => '🧠',
                'color_from' => '#EEE6F6',
                'color_to' => '#E1EEFB',
                'reading_minutes' => 4,
                'excerpt' => 'Kinderen met NLD praten vaak vlot, maar lopen vast bij het overzicht. Wat betekent dat in het dagelijks leven?',
                'body' => "NLD (non-verbale leerstoornis) is verwarrend, juist omdat kinderen vaak goed kunnen praten. Het knelpunt zit elders: bij het overzien van situaties, ruimtelijk inzicht, plannen en het oppikken van non-verbale signalen.\n\nIn de praktijk kan dit betekenen dat huiswerk plannen lastig is, of dat sociale situaties verwarrend zijn omdat lichaamstaal niet vanzelf wordt 'gelezen'.\n\nWat helpt: maak het impliciete expliciet. Benoem stappen, gebruik checklists en leg sociale situaties in woorden uit. Bouw voort op de sterke kant - taal - om de lastigere kanten te ondersteunen.",
            ],
            [
                'title' => 'ADHD: ruimte voor veel energie',
                'category' => 'ADHD',
                'emoji' => '⚡',
                'color_from' => '#FBEFD8',
                'color_to' => '#FCE7EB',
                'reading_minutes' => 4,
                'excerpt' => 'Druk, impulsief en snel afgeleid - maar ook creatief, enthousiast en spontaan. Tips voor rust in huis.',
                'body' => "Kinderen met ADHD hebben vaak een hoofd vol ideeën en een lijf vol energie. Dat is soms vermoeiend, maar ook prachtig: ze zijn enthousiast, creatief en spontaan.\n\nStructuur geeft rust. Vaste momenten, korte duidelijke opdrachten en voldoende beweging helpen enorm. Verdeel grote taken in kleine stapjes en vier elke stap.\n\nWees ook lief voor jezelf. Een huis met veel energie vraagt veel van ouders. Zoek steun, deel je ervaringen en onthoud: gedrag is communicatie. Achter elke drukte zit een kind dat gezien wil worden.",
            ],
            [
                'title' => 'Rust in het ouderschap: 5 zachte avondroutines',
                'category' => 'Inspiratie',
                'emoji' => '🌙',
                'color_from' => '#E4F3E9',
                'color_to' => '#F3F0FB',
                'reading_minutes' => 3,
                'excerpt' => 'Het thema van de maand in de community. Vijf kleine rituelen voor meer rust aan het eind van de dag.',
                'body' => "Rust in het ouderschap begint vaak bij kleine, vaste rituelen. Hier zijn vijf zachte avondroutines die ouders in onze community delen.\n\n1. Dim het licht een uur voor bedtijd - het seintje voor lijf en hoofd dat de dag erop zit.\n2. Eén vast boekje of liedje, elke avond hetzelfde. Voorspelbaarheid stelt gerust.\n3. Een 'hoogtepunt van de dag' delen, hoe klein ook. Samen terugkijken verbindt.\n4. Telefoon weg tijdens het laatste halfuur. De aandacht is dan helemaal voor elkaar.\n5. Een kort momentje voor jezelf nadat de kinderen slapen - thee, een boek of gewoon stilte.\n\nWelke routine geeft jullie rust? Deel het in de community. 💛",
            ],
        ];

        foreach ($articles as $i => $a) {
            Article::updateOrCreate(
                ['slug' => Str::slug($a['title'])],
                array_merge($a, [
                    'author_name' => 'Stephanie van der Kooij',
                    'published_at' => now()->subDays(count($articles) - $i),
                ])
            );
        }
    }
}
