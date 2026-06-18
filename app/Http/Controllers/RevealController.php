<?php

namespace App\Http\Controllers;

use App\Models\BabyName;
use App\Models\RevealIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RevealController extends Controller
{
    private array $categoryLabels = [
        'vertellen' => [
            'partner' => 'Aan je partner',
            'familie' => 'Aan de familie',
            'vrienden' => 'Aan vrienden',
            'creatief' => 'Creatief & verrassend',
            'humoristisch' => 'Humoristisch & speels',
            'social' => 'Social media',
            'intiem' => 'Klein & intiem',
            'origineel' => 'Super origineel',
            'interactief' => 'Interactief & grappig',
            'ontroerend' => 'Ontroerend & emotioneel',
            'supercute' => 'Supercute',
        ],
        'gender' => [
            'klassiek' => 'Klassiek',
            'schattig' => 'Schattig & intiem',
            'feestelijk' => 'Feestelijk',
            'eetbaar' => 'Eetbaar',
            'uniek' => 'Uniek & creatief',
            'supercute' => 'Supercute',
            'romantisch' => 'Romantisch',
            'kinderen' => 'Met kinderen',
            'interactie' => 'Interactie met gasten',
        ],
    ];

    public function index(): Response
    {
        $all = RevealIdea::orderBy('position')->get()->groupBy('group');

        $buildGroup = function (string $group) use ($all) {
            $byCategory = ($all[$group] ?? collect())->groupBy('category');

            return collect($this->categoryLabels[$group])
                ->map(fn ($label, $key) => [
                    'key' => $key,
                    'label' => $label,
                    'ideas' => ($byCategory[$key] ?? collect())->map(fn (RevealIdea $i) => [
                        'title' => $i->title,
                        'description' => $i->description,
                    ])->values(),
                ])
                ->filter(fn ($cat) => $cat['ideas']->isNotEmpty())
                ->values();
        };

        $cards = ($all['kaartje'] ?? collect())->map(fn (RevealIdea $i) => [
            'title' => $i->title,
            'description' => $i->description,
        ])->values();

        $user = Auth::user();
        $favIds = $user
            ? $user->favouriteNames()->pluck('baby_names.id')->flip()
            : collect();

        $names = BabyName::orderBy('name')->get()
            ->groupBy('type')
            ->map(fn ($group) => $group->map(fn (BabyName $n) => [
                'id' => $n->id,
                'name' => $n->name,
                'fav' => $favIds->has($n->id),
            ])->values());

        return Inertia::render('Reveal', [
            'vertellen' => $buildGroup('vertellen'),
            'gender' => $buildGroup('gender'),
            'cards' => $cards,
            'names' => [
                'meisjes' => $names['meisjes'] ?? [],
                'jongens' => $names['jongens'] ?? [],
                'tweeling' => $names['tweeling'] ?? [],
            ],
            'favCount' => $favIds->count(),
        ]);
    }

    public function toggleFavourite(BabyName $name): RedirectResponse
    {
        Auth::user()->favouriteNames()->toggle($name->id);

        return back();
    }
}
