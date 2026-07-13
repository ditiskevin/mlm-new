<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ListingStatusController extends Controller
{
    public const STATUSES = ['beschikbaar', 'gereserveerd', 'verkocht'];

    /**
     * Update the status of a listing. Only the owner may do this.
     */
    public function update(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($request->user()->id === $listing->user_id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        $listing->update(['status' => $data['status']]);

        return back();
    }
}
