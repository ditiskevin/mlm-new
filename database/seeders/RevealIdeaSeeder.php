<?php

namespace Database\Seeders;

use App\Models\RevealIdea;
use Illuminate\Database\Seeder;

class RevealIdeaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'vertellen' => [
                'partner' => [
                    ['Speciaal diner met hint', 'Kook een speciaal diner en leg op het bord een sokje of speentje met een briefje: "Raad eens?"'],
                    ['Mini-schoentjes op het kussen', 'Leg een paar babyschoentjes op het kussen of bord tijdens het ontbijt.'],
                    ['Puzzel of raadsel', 'Geef een gepersonaliseerde puzzel die eindigt met "We krijgen een baby!"'],
                    ['"Papa in de maak"-shirt', 'Laat een T-shirt maken en laat je partner het ontdekken.'],
                    ['Fotomoment via WhatsApp', 'Stuur een foto van jezelf met een bordje "Je wordt papa!" als verrassing.'],
                    ['Verrassing in het glas', 'Vraag je partner een drankje te halen en stop een mini-flesje of schoentje in het glas of de tas.'],
                    ['Koffiemok-hint', 'Geef een mok met "Ouders in opleiding" of "Papa vanaf [maand]".'],
                    ['Bioscoop-aankondiging', 'Geef kaartjes voor "Onze nieuwste productie: Baby [Achternaam]".'],
                    ['Filmavond-bord', 'Plaats een pop-up bord bij de tv: "Première: Baby [Achternaam] binnenkort!"'],
                    ['Eigen flesetiket', 'Vervang een etiket door een zelfgemaakt label: "Papa\'s nieuwe avontuur begint [maand]!"'],
                    ['Raadsel op de koelkast', 'Laat een briefje achter: "Binnenkort 1+1=3, weet je het al?"'],
                    ['Kussensurprise', 'Leg een knuffel en speentje op het kussen met een kaartje: "Binnenkort zijn we met z\'n drieën!"'],
                    ['Letterbak of krijtbord', 'Zet de boodschap neer: "Onze kleine komt eraan!"'],
                ],
                'familie' => [
                    ['Foto-aankondiging', 'Stuur een foto van een baby-itemje met briefje: "Kan niet wachten om jullie kleinkind te laten zien!"'],
                    ['Feestelijke toast', 'Nodig ze uit voor een toast en onthul het met een taart waarop "We krijgen een baby!" staat.'],
                    ['Quiz met slotvraag', 'Organiseer een quiz over jullie gezin en laat de laatste vraag het nieuws onthullen.'],
                    ['Babyboekje cadeau', 'Geef een leeg babyboekje: "Jij mag de eerste pagina invullen!"'],
                    ['Echo in een lijstje', 'Vervang een familiefoto door de echo: "Binnenkort extra familie erbij!"'],
                    ['Tijdcapsule', 'Geef een doosje met briefje: "Bewaar dit voor over 9 maanden…"'],
                    ['Post-it hints', 'Plak hints door het huis of op hun auto: "Er groeit iets moois…"'],
                    ['Koffie met koekje', 'Serveer thee met een koekje: "Je wordt oma/opa/tante/oom!"'],
                    ['Seizoensdecoratie-hint', 'Verwerk mini-schoentjes of sokjes in de kerst-, paas- of herfstdecoratie.'],
                    ['Babykleertjes ingepakt', 'Verpak mini-kleertjes: "Voor een nieuwe kleine aanwinst!"'],
                    ['Mini-schoentje voor oma', 'Geef een mini-schoentje met brief: "Jij krijgt er een kleinkind bij!"'],
                    ['Taart met babyprint', 'Laat een taart maken met kleine voetjes of handjes erop.'],
                ],
                'vrienden' => [
                    ['Social media post', 'Plaats babyschoentjes of een echo met een lieve of grappige tekst.'],
                    ['Mini surprise-pakketje', 'Stuur een pakketje met baby-itemjes en een kaartje: "Raad eens wat er binnenkort komt?"'],
                    ['Babynieuws-borrel', 'Nodig ze uit voor een brunch of borrel en onthul het nieuws creatief.'],
                    ['Subtiele hints', 'Geef hints tijdens een bijeenkomst: "Ik krijg binnenkort een excuus om nachten op te blijven!"'],
                    ['Throwback met twist', 'Plaats een babyfoto van jezelf: "Het begon hier… en nu komt er een nieuwe!"'],
                    ['Huisdier als boodschapper', 'Laat je hond of kat een bandana dragen: "Wij krijgen een broertje/zusje!"'],
                    ['Raadspel met hints', 'Stuur een quiz waarbij het antwoord het grote nieuws onthult.'],
                    ['Grappige quote-kaart', '"We hebben besloten onze eigen mini-versie te produceren!"'],
                    ['Emoji-aankondiging', 'Stuur alleen 👶🍼❤️ en laat ze raden.'],
                    ['Ballon met briefje', 'Stop een mini-schoentje in een ballon en laat ze \'m doorprikken.'],
                ],
                'creatief' => [
                    ['Echo in een ballon', 'Stop een afdruk van de echo in een ballon en laat hem doorprikken.'],
                    ['"Coming soon"-buikfoto', 'Maak een foto van je buik met krijt of stickers: "Coming soon…"'],
                    ['Post-it bom', 'Plak kleine briefjes met hints door het huis of op het werk.'],
                    ['Verstoppertje', 'Verstop babyspulletjes of briefjes in huis of tuin om te ontdekken.'],
                    ['Buikgroei-fotoreeks', 'Maak een serie foto\'s van je buikgroei en sluit af met de echo.'],
                    ['Handafdruk babypopje', 'Laat een mini-handafdruk maken met briefje: "Binnenkort de echte!"'],
                    ['Filmtrailer-stijl video', 'Maak een korte video: "Coming soon… Baby [Achternaam]".'],
                    ['Mini-advertentie', 'Maak een nepflyer: "Te huur: toekomstige ouders, startdatum [maand]".'],
                    ['Raamstickers', 'Plak "Baby incoming" op het raam zodat iedereen het ziet.'],
                    ['Fake magazine-cover', 'Maak een tijdschriftcover met jullie en de tekst "Binnenkort uitbreiding!"'],
                ],
                'humoristisch' => [
                    ['Sokken-speentje hint', 'Geef baby-sokken: "Tijd om groter te groeien!"'],
                    ['"Mommy loading"-shirt', 'Draag een shirt met "Mommy loading… 100% verwacht in [maand]".'],
                    ['Koffie-aankondiging', 'Schenk koffie in een mok: "Ouders in opleiding".'],
                    ['Netflix-stijl poster', '"Nieuwe aflevering binnenkort: Baby [Achternaam], seizoensstart [maand]".'],
                    ['Speelgoed-hint', 'Zet een rijdende pop of babyspeeltje voor de deur of op het bed met een kaartje.'],
                    ['Kookboek-hint', 'Stop een mini-lepeltje of babyfles in een kookboek: "Nieuw recept: Baby [Achternaam]".'],
                    ['Snoepjes-verrassing', 'Verstop babychocolaatjes in een doosje: "Niet opeten, er komt er een echte aan!"'],
                    ['Mini-poster', 'Hang een poster op: "Nieuwe bewoner arriveert [maand]!"'],
                ],
                'social' => [
                    ['Echo als puzzel', 'Upload de echo in puzzelvorm zodat volgers het langzaam ontdekken.'],
                    ['Throwback met twist', 'Post een babyfoto van jezelf: "Het begon allemaal hier… en nu komt er een nieuwe!"'],
                    ['Dieren als boodschapper', 'Laat je huisdier een briefje dragen: "Wij krijgen een broertje/zusje!"'],
                    ['Stop-motion video', 'Onthul het nieuws geleidelijk met speelgoed, sokjes of babyflessen.'],
                    ['TikTok-dansje', 'Maak een dansje dat het nieuws subtiel hint met een T-shirt of ballon.'],
                    ['AR-filter', 'Ontwerp een filter met baby-itemjes en laat volgers het ontdekken.'],
                    ['Emoji-verhaal', 'Vertel een heel verhaal in emoji\'s dat eindigt met 👶🍼❤️'],
                ],
                'intiem' => [
                    ['Cadeautje in een doosje', 'Stop een speentje, sokje of mini-flesje in een doosje: "Binnenkort bij ons…"'],
                    ['Briefje in de lunchbox', 'Leg een kaartje in de tas of lunchbox: "Raad eens wat er groeit?"'],
                    ['Avondwandeling-verrassing', 'Hang een ballon of bord langs het pad met het nieuws.'],
                    ['Echo in fotolijstje', 'Geef het eerste echo-fotootje cadeau: "Wij worden ouders!"'],
                    ['Kaartspel-hint', 'Speel een spel en vervang een kaart met "We krijgen een baby!"'],
                    ['Handgeschreven brief', 'Schrijf een brief met je gevoelens over de zwangerschap en overhandig die persoonlijk.'],
                    ['Mijlpaal-kalender', 'Geef een kalender met gemarkeerde weken, eindigend met "Je wordt oma/opa…"'],
                    ['Sterren- of maanthema', 'Geef een nachtlampje met maan en sterren: "Er komt een klein sterretje bij ons thuis."'],
                ],
                'origineel' => [
                    ['QR-code verrassing', 'Maak een QR-code naar een video of foto van de echo en laat ze scannen.'],
                    ['Filmtrailer surprise', 'Monteer een trailer-stijl video met dramatische muziek: "Coming soon… Baby [Achternaam]".'],
                    ['Mini-schatkist', 'Verstop een doosje met babyspulletjes als schat: "Jullie zoektocht eindigt hier!"'],
                    ['Fotolijst-puzzel', 'Druk de echo op puzzelstukjes en laat hen het samen leggen.'],
                    ['Ballonburst', 'Stop confetti, echo-afdruk of mini-schoentjes in een ballon en laat ze doorprikken.'],
                ],
                'interactief' => [
                    ['Mini escape-room', 'Organiseer een escape-room in huis met het grote nieuws als eindoplossing.'],
                    ['Raadselspel via WhatsApp', 'Stuur een serie hints die eindigen met "We krijgen een baby!"'],
                    ['Boodschap in de cake', 'Bak een cake en laat iemand de hap nemen die de boodschap binnenin onthult.'],
                    ['Fotoboodschap onder glazen', 'Plaats foto\'s van de echo onder glazen tijdens een borrel.'],
                    ['Kledinglijn-hint', 'Geef een shirt: "Ik ben binnenkort op wacht… Baby onderweg!"'],
                ],
                'ontroerend' => [
                    ['Sterrenhemel-hint', 'Geef een nachtlampje of sterprojector met kaartje: "Een klein sterretje komt erbij…"'],
                    ['Brief aan oma/opa', 'Schrijf een emotionele brief waarin je het nieuws aankondigt.'],
                    ['Samen groeikalender', 'Geef een kalender met elke maand een belangrijke mijlpaal van de zwangerschap.'],
                    ['Echo-schilderij', 'Laat de echo printen of schilderen op een mini-schilderij.'],
                    ['Fotoboek-verrassing', 'Maak een fotoboek van jullie verhaal en eindig met de echo: "Binnenkort uitbreiding!"'],
                ],
                'supercute' => [
                    ['Foto met voetjes', 'Maak een foto van je voeten naast mini-sokjes: "Binnenkort zijn we met z\'n drieën!"'],
                    ['Mini-boekje "Ons verhaal"', 'Eindig het boekje met: "Nieuw hoofdstuk: Baby [Achternaam]!"'],
                    ['Babypop-surprise', 'Zet een kleine pop op tafel: "Raad eens wie er binnenkort echt is?"'],
                    ['Sterrenlicht-hint', 'Geef een sterprojector: "Er komt een klein sterretje bij ons thuis!"'],
                    ['Handafdrukken-hint', 'Maak een mini-handafdruk van een babypopje: "Binnenkort echte handjes!"'],
                ],
            ],

            'gender' => [
                'klassiek' => [
                    ['Ballonnen-doos', 'Open een grote doos waar roze of blauwe ballonnen uit vliegen.'],
                    ['Confetti-shooters', 'Laat iedereen tegelijk een confettikanon afschieten.'],
                    ['Taart of cupcakes', 'Snijd een taart aan met een roze of blauwe binnenkant.'],
                    ['Piñata', 'Sla een piñata kapot gevuld met snoep of confetti in de onthullingskleur.'],
                    ['Rookbommen', 'Laat buiten een wolk blauwe of roze rook knallen.'],
                ],
                'schattig' => [
                    ['Baby-outfit', 'Open een cadeauzak met een romper, sokjes of mutsje in de kleur.'],
                    ['Knuffel met lintje', 'Geef een knuffel met een roze of blauw lint.'],
                    ['Ingelijste echo', 'Laat een echo-foto zien met gekleurde achtergrond.'],
                    ['Gekleurde lichtjes', 'Zet een doos aan met roze of blauwe lampjes die oplichten.'],
                    ['Koffiemok-bodem', 'Laat familie uit mokken drinken waar op de bodem de kleur verschijnt.'],
                ],
                'feestelijk' => [
                    ['Team roze vs. team blauw', 'Laat gasten een sticker kiezen en maak een groepsfoto vóór de onthulling.'],
                    ['Ballonnen oplaten', 'Laat (biologisch afbreekbare) ballonnen los in de onthullingskleur.'],
                    ['Glowsticks', 'Deel witte buisjes uit die pas oplichten als iedereen tegelijk breekt.'],
                    ['Confetti in ballonnen', 'Prik een grote ballon kapot met gekleurde confetti erin.'],
                    ['Schuimparty', 'Een schuimkanon dat roze of blauw schuim spuit.'],
                ],
                'eetbaar' => [
                    ['Chocoladebol', 'Tik een chocoladebol open met een hamertje, gevuld met gekleurde snoepjes.'],
                    ['Donuts', 'Met roze of blauwe vulling.'],
                    ['Milkshake of mocktail', 'Laat het drankje vanbinnen de onthullingskleur hebben.'],
                    ['Pizza-surprise', 'Laat een pizza bezorgen met de vulling in hartvorm en de juiste kleur.'],
                    ['Taart aansnijden', 'Snijd de taart open zodat de gekleurde crème zichtbaar wordt.'],
                ],
                'uniek' => [
                    ['Verf-schilderij', 'Laat een ballon met verf kapot knallen tegen een canvas → kunstwerk én reveal!'],
                    ['Watergevecht', 'Waterballonnen gevuld met roze of blauw water.'],
                    ['Huisdier reveal', 'Hond met roze/blauw bandana of kat met een strikje.'],
                    ['Sport reveal', 'Een voetbal of golfbal die openbarst in de kleur bij een trap of slag.'],
                    ['Scratch cards', 'Laat gasten kraskaarten openkrassen met het geslacht erop.'],
                ],
                'supercute' => [
                    ['Knuffelbeertje met lintje', 'Geef een knuffel met een roze of blauw lint om z\'n nek.'],
                    ['Mini-sokjes of schoentjes', 'Pak een doosje uit met mini-sokjes in de onthullingskleur.'],
                    ['Pop-it ballon', 'Prik een grote witte ballon kapot; kleine roze of blauwe ballonnen vallen eruit.'],
                    ['Muziekdoosje', 'Bij openen speelt een liedje en verschijnt een kaartje: "It\'s a boy/girl!"'],
                    ['Rompertje met tekst', '"Hi daddy, see you soon!" in de kleur van het geslacht.'],
                    ['Storybook surprise', 'Geef een prentenboek met de onthulling op de laatste bladzijde.'],
                    ['Gevulde babyfles', 'Vul een doorzichtige fles met snoep of confetti in de juiste kleur.'],
                    ['Houten puzzel', 'Bij de laatste stukjes verschijnt "It\'s a boy/girl!"'],
                ],
                'romantisch' => [
                    ['Kaarslicht-onthulling', 'Steek samen waxinelichtjes aan die een hart vormen in roze of blauw.'],
                    ['Liefdesbrief + kaartje', 'Schrijf elkaar een brief en lees tegelijk "It\'s a boy/girl!"'],
                    ['Sterrenprojector', 'Vul de kamer met een roze of blauwe sterrenhemel.'],
                    ['Rozenblaadjes-regen', 'Laat bij het openen van een doos roze of blauwe blaadjes vallen.'],
                    ['Hartjesballon', 'Een grote witte hartballon die barst met gekleurde confetti.'],
                    ['Champagne of mocktail', 'Vul samen glazen met een drankje dat de onthullingskleur krijgt.'],
                    ['Sneeuwbol', 'Schud een glazen bol met roze of blauw glitter.'],
                ],
                'kinderen' => [
                    ['Ballon prikken', 'Laat de kinderen een grote ballon kapot prikken waar gekleurde confetti uitkomt.'],
                    ['Verfhandjes op canvas', 'Laat een broertje/zusje verfhandjes zetten in de juiste kleur.'],
                    ['Samen de doos openen', 'Laat de kinderen samen een doos openen waar ballonnen uit vliegen.'],
                ],
                'interactie' => [
                    ['Team roze vs. team blauw', 'Laat iedereen kiezen en een groepsfoto maken vóór de onthulling.'],
                    ['Scratchcards', 'Gasten ontdekken het geslacht tegelijk door te krassen.'],
                    ['Groot raadspel', 'Zet een bord neer waar gasten hun gok noteren, met een prijs voor wie goed raadt.'],
                    ['Confetti-regen', 'Huur een machine die confetti in de juiste kleur over de dansvloer laat vallen.'],
                    ['Waterballonnen-gevecht', 'Een fun battle met ballonnen gevuld met roze of blauw water.'],
                ],
            ],

            'kaartje' => [
                'teksten' => [
                    ['Klein maar groots in liefde', 'Lief & knuffelig'],
                    ['Een sterretje is geland', 'Poëtisch & sprookjesachtig'],
                    ['Sleep, cuddle, repeat', 'Speels & humoristisch'],
                    ['Eén klein hartje, oneindig veel liefde', 'Modern & warm'],
                    ['Dubbel geluk, dubbel liefde', 'Voor een tweeling'],
                    ['Ons zonnestraaltje is geland!', 'Lief & origineel'],
                ],
            ],
        ];

        foreach ($data as $group => $categories) {
            foreach ($categories as $category => $ideas) {
                foreach ($ideas as $i => [$title, $description]) {
                    RevealIdea::updateOrCreate(
                        ['group' => $group, 'category' => $category, 'position' => $i],
                        ['title' => $title, 'description' => $description]
                    );
                }
            }
        }
    }
}
