<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // The profile type is either the generic 'ouder' or an audience slug.
        $audience = $user->parent_type && $user->parent_type !== 'ouder'
            ? Audience::where('slug', $user->parent_type)->first()
            : null;

        return Inertia::render('Dashboard', [
            'suggestedAudience' => $audience ? [
                'name' => $audience->name,
                'slug' => $audience->slug,
                'emoji' => $audience->emoji,
                'tagline' => $audience->tagline,
                'color_from' => $audience->color_from,
                'color_to' => $audience->color_to,
            ] : null,
        ]);
    }
}
