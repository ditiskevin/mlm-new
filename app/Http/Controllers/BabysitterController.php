<?php

namespace App\Http\Controllers;

use App\Models\Babysitter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BabysitterController extends Controller
{
    public function index(Request $request): Response
    {
        $location = $request->query('location');
        $search = $request->query('q');
        $type = in_array($request->query('type'), array_keys(Babysitter::TYPES), true)
            ? $request->query('type')
            : null;

        $query = Babysitter::query()->latest();
        if ($type) {
            $query->where('type', $type);
        }
        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }
        if ($search) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('bio', 'like', "%{$search}%"));
        }

        return Inertia::render('Babysitter/Index', [
            'sitters' => $query->get()->map(fn (Babysitter $b) => $this->card($b)),
            'locations' => Babysitter::query()->select('location')->distinct()->orderBy('location')->pluck('location'),
            'filters' => ['location' => $location, 'q' => $search, 'type' => $type],
            'counts' => [
                'aanbod' => Babysitter::where('type', 'aanbod')->count(),
                'gezocht' => Babysitter::where('type', 'gezocht')->count(),
            ],
        ]);
    }

    public function show(Babysitter $babysitter): Response
    {
        return Inertia::render('Babysitter/Show', [
            'sitter' => array_merge($this->card($babysitter), [
                'bio' => $babysitter->bio,
                'owner_id' => $babysitter->user_id,
                'canDelete' => auth()->id() && auth()->id() === $babysitter->user_id,
            ]),
        ]);
    }

    public function create(Request $request): Response
    {
        $type = $request->query('type') === 'gezocht' ? 'gezocht' : 'aanbod';

        return Inertia::render('Babysitter/Create', ['type' => $type]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:'.implode(',', array_keys(Babysitter::TYPES))],
            'name' => ['required', 'string', 'max:80'],
            'age' => ['nullable', 'integer', 'min:14', 'max:99'],
            'location' => ['required', 'string', 'max:80'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:200'],
            // Sitter-only fields, ignored for a "gezocht" request.
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'has_vog' => ['boolean'],
            'availability' => ['required', 'string', 'max:120'],
            'bio' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $isOffer = $data['type'] === 'aanbod';

        $sitter = Babysitter::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'name' => $data['name'],
            'age' => $isOffer ? ($data['age'] ?? null) : null,
            'location' => $data['location'],
            'hourly_rate' => $data['hourly_rate'] ?? null,
            'experience_years' => $isOffer ? ($data['experience_years'] ?? 0) : 0,
            'availability' => $data['availability'],
            'has_vog' => $isOffer ? ($data['has_vog'] ?? false) : false,
            'bio' => $data['bio'],
            'avatar_color' => $user->avatar_color ?: ($isOffer ? '#9AD3AC' : '#F7A8B5'),
        ]);

        return redirect()->route('babysitters.show', $sitter);
    }

    public function destroy(Babysitter $babysitter): RedirectResponse
    {
        abort_unless(auth()->id() === $babysitter->user_id, 403);
        $babysitter->delete();

        return redirect()->route('babysitters.index');
    }

    private function card(Babysitter $b): array
    {
        return [
            'id' => $b->id,
            'type' => $b->type,
            'type_label' => Babysitter::TYPES[$b->type] ?? 'Oppas',
            'name' => $b->name,
            'age' => $b->age,
            'location' => $b->location,
            'hourly_rate' => $b->hourly_rate !== null ? number_format((float) $b->hourly_rate, 2, ',', '.') : null,
            'experience_years' => $b->experience_years,
            'availability' => $b->availability,
            'has_vog' => $b->has_vog,
            'avatar_color' => $b->avatar_color,
            'initial' => Str::upper(Str::substr($b->name, 0, 1)),
            'excerpt' => Str::limit($b->bio, 110),
        ];
    }
}
