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

        $query = Babysitter::query()->latest();
        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }
        if ($search) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('bio', 'like', "%{$search}%"));
        }

        return Inertia::render('Babysitter/Index', [
            'sitters' => $query->get()->map(fn (Babysitter $b) => $this->card($b)),
            'locations' => Babysitter::query()->select('location')->distinct()->orderBy('location')->pluck('location'),
            'filters' => ['location' => $location, 'q' => $search],
        ]);
    }

    public function show(Babysitter $babysitter): Response
    {
        return Inertia::render('Babysitter/Show', [
            'sitter' => array_merge($this->card($babysitter), [
                'bio' => $babysitter->bio,
                'canDelete' => auth()->id() && auth()->id() === $babysitter->user_id,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Babysitter/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'age' => ['nullable', 'integer', 'min:14', 'max:99'],
            'location' => ['required', 'string', 'max:80'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'availability' => ['required', 'string', 'max:120'],
            'has_vog' => ['boolean'],
            'bio' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        $sitter = Babysitter::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'age' => $data['age'] ?? null,
            'location' => $data['location'],
            'hourly_rate' => $data['hourly_rate'] ?? null,
            'experience_years' => $data['experience_years'] ?? 0,
            'availability' => $data['availability'],
            'has_vog' => $data['has_vog'] ?? false,
            'bio' => $data['bio'],
            'avatar_color' => $user->avatar_color ?: '#9AD3AC',
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
