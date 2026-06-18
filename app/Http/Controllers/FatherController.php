<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FatherController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $checkedIds = $user
            ? $user->checkedItems()->pluck('checklist_items.id')->flip()
            : collect();

        $activities = ChecklistItem::where('type', 'vader')
            ->orderBy('position')
            ->get()
            ->groupBy('category')
            ->map(fn ($items, $category) => [
                'name' => $category,
                'items' => $items->map(fn (ChecklistItem $it) => [
                    'id' => $it->id,
                    'label' => $it->label,
                    'done' => $checkedIds->has($it->id),
                ])->values(),
            ])->values();

        $trimesters = [
            [
                'label' => '1e trimester',
                'weeks' => 'Week 1 – 13',
                'emoji' => '🌱',
                'color' => '#FCE7EB',
                'tips' => [
                    'Ga mee naar de eerste afspraken bij de verloskundige - samen horen jullie de eerste hartslag.',
                    'Wees begripvol bij misselijkheid en vermoeidheid; neem taken in huis over.',
                    'Lees je in over de zwangerschap zodat je weet wat er gebeurt en kunt meedenken.',
                    'Stop samen met alcohol als steuntje in de rug - dat maakt het makkelijker vol te houden.',
                ],
            ],
            [
                'label' => '2e trimester',
                'weeks' => 'Week 14 – 27',
                'emoji' => '🤰',
                'color' => '#E4F3E9',
                'tips' => [
                    'Voel de eerste schopjes mee - leg je hand op de buik en praat tegen je kindje.',
                    'Ga mee naar de 20-wekenecho; een bijzonder moment om samen te beleven.',
                    'Begin samen met klussen: babykamer, autostoeltje en kinderwagen uitzoeken.',
                    'Plan nog een leuk uitje samen (een "babymoon") nu de energie terug is.',
                ],
            ],
            [
                'label' => '3e trimester',
                'weeks' => 'Week 28 – 40',
                'emoji' => '🎉',
                'color' => '#FBEFD8',
                'tips' => [
                    'Pak samen de vluchttas in en leg de route naar het ziekenhuis vast.',
                    'Oefen ademhalings- en ontspanningstechnieken zodat je tijdens de bevalling kunt steunen.',
                    'Bespreek het bevalplan: wat zijn jullie wensen en wat is jouw rol?',
                    'Zorg goed voor jezelf en je partner - rust, gezond eten en geduld zijn nu goud waard.',
                ],
            ],
        ];

        $support = [
            ['icon' => '👂', 'title' => 'Luister zonder te willen oplossen', 'text' => 'Soms wil je partner vooral even haar verhaal kwijt. Luisteren en erkennen helpt vaak meer dan tips geven.'],
            ['icon' => '🧹', 'title' => 'Neem praktische zorgen over', 'text' => 'Boodschappen, koken en huishouden: hoe minder zij hoeft te regelen, hoe meer rust ze heeft.'],
            ['icon' => '💬', 'title' => 'Praat over jullie verwachtingen', 'text' => 'Hoe verdelen jullie straks de zorg en het slaaptekort? Erover praten voorkomt verrassingen.'],
            ['icon' => '💛', 'title' => 'Vergeet jezelf niet', 'text' => 'Ook als partner mag je twijfels en spanning voelen. Deel ze - je staat er samen voor.'],
        ];

        return Inertia::render('Father', [
            'trimesters' => $trimesters,
            'support' => $support,
            'activities' => $activities,
        ]);
    }
}
