<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MarketplaceController extends Controller
{
    public const CATEGORIES = ['Kleding', 'Speelgoed', 'Babyspullen', 'Meubels', 'Boeken', 'Buggy & vervoer', 'Overig'];

    public const OFFER_TYPES = ['aanbod' => 'Te koop', 'ruil' => 'Ruilen', 'gratis' => 'Gratis', 'gevraagd' => 'Gevraagd'];

    private array $emojis = ['Kleding' => '👕', 'Speelgoed' => '🧸', 'Babyspullen' => '🍼', 'Meubels' => '🪑', 'Boeken' => '📚', 'Buggy & vervoer' => '🛒', 'Overig' => '📦'];

    public function index(Request $request): Response
    {
        $category = $request->query('category');
        $type = $request->query('type');
        $search = $request->query('q');

        $query = Listing::query()->latest();
        if ($category) {
            $query->where('category', $category);
        }
        if ($type) {
            $query->where('offer_type', $type);
        }
        if ($search) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
        }

        return Inertia::render('Marketplace/Index', [
            'listings' => $query->get()->map(fn (Listing $l) => $this->card($l)),
            'categories' => self::CATEGORIES,
            'offerTypes' => self::OFFER_TYPES,
            'filters' => ['category' => $category, 'type' => $type, 'q' => $search],
        ]);
    }

    public function show(Listing $listing): Response
    {
        return Inertia::render('Marketplace/Show', [
            'listing' => array_merge($this->card($listing), [
                'description' => $listing->description,
                'condition' => $listing->condition,
                'canDelete' => auth()->id() && auth()->id() === $listing->user_id,
                'created' => $listing->created_at->translatedFormat('j F Y'),
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Marketplace/Create', [
            'categories' => self::CATEGORIES,
            'offerTypes' => self::OFFER_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'offer_type' => ['required', 'in:'.implode(',', array_keys(self::OFFER_TYPES))],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'condition' => ['nullable', 'string', 'max:40'],
            'location' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'max:6144'],
        ]);

        $user = $request->user();

        $listing = Listing::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'category' => $data['category'],
            'offer_type' => $data['offer_type'],
            'price' => in_array($data['offer_type'], ['gratis', 'gevraagd']) ? null : ($data['price'] ?? null),
            'condition' => $data['condition'] ?? null,
            'location' => $data['location'],
            'description' => $data['description'],
            'image_path' => $request->hasFile('image') ? $request->file('image')->store('listings', 'r2') : null,
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#F7A8B5',
            'emoji' => $this->emojis[$data['category']] ?? '📦',
        ]);

        return redirect()->route('marketplace.show', $listing);
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        abort_unless(auth()->id() === $listing->user_id, 403);
        $listing->delete();

        return redirect()->route('marketplace.index');
    }

    private function card(Listing $l): array
    {
        return [
            'title' => $l->title,
            'slug' => $l->slug,
            'category' => $l->category,
            'offer_type' => $l->offer_type,
            'offer_label' => self::OFFER_TYPES[$l->offer_type] ?? $l->offer_type,
            'price' => $l->price !== null ? number_format((float) $l->price, 2, ',', '.') : null,
            'location' => $l->location,
            'author_name' => $l->author_name,
            'avatar_color' => $l->avatar_color,
            'emoji' => $l->emoji,
            'image_url' => $l->image_url,
            'excerpt' => Str::limit($l->description, 90),
        ];
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Listing::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
