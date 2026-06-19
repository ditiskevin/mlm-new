<?php

namespace App\Http\Middleware;

use App\Models\Audience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                // Lazily evaluated so it only runs when a page needs it.
                'unreadMessages' => fn () => $request->user()?->unreadConversationsCount() ?? 0,
            ],
            'audiences' => Schema::hasTable('audiences')
                ? Audience::orderBy('position')->get(['slug', 'name', 'emoji'])
                : [],
            // One-shot toast messages set with ->with('success'|'error', ...).
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            // Sidebar badge counts — only computed on admin pages.
            'adminBadges' => fn () => ($request->user()?->is_admin && $request->routeIs('admin.*'))
                ? \App\Http\Controllers\AdminController::sidebarBadges()
                : null,
        ];
    }
}
