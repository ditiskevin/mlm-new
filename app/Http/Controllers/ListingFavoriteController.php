<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ListingFavoriteController extends Controller
{
    /**
     * Toggle a listing_favorites row for the authenticated user.
     */
    public function toggle(Request $request, Listing $listing): RedirectResponse
    {
        $userId = $request->user()->id;

        $existing = DB::table('listing_favorites')
            ->where('user_id', $userId)
            ->where('listing_id', $listing->id)
            ->first();

        if ($existing) {
            DB::table('listing_favorites')->where('id', $existing->id)->delete();
        } else {
            DB::table('listing_favorites')->insert([
                'user_id' => $userId,
                'listing_id' => $listing->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back();
    }

    /**
     * Render the authenticated user's favorited listings.
     */
    public function index(Request $request): Response
    {
        $userId = $request->user()->id;

        $listings = Listing::query()
            ->whereIn('id', function ($q) use ($userId) {
                $q->select('listing_id')
                    ->from('listing_favorites')
                    ->where('user_id', $userId);
            })
            ->latest()
            ->get()
            ->map(fn (Listing $l) => [
                'title' => $l->title,
                'slug' => $l->slug,
                'category' => $l->category,
                'offer_type' => $l->offer_type,
                'offer_label' => MarketplaceController::OFFER_TYPES[$l->offer_type] ?? $l->offer_type,
                'price' => $l->price !== null ? number_format((float) $l->price, 2, ',', '.') : null,
                'location' => $l->location,
                'emoji' => $l->emoji,
                'image_url' => $l->image_url,
                'excerpt' => Str::limit($l->description, 90),
                'status' => $l->status,
                'favorited' => true,
            ]);

        return Inertia::render('Marketplace/Favorites', [
            'listings' => $listings,
        ]);
    }
}
