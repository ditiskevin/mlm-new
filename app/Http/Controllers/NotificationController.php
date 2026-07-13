<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /** Full notifications page. */
    public function index(Request $request): Response
    {
        return Inertia::render('Notifications/Index', [
            'notifications' => $this->recent($request, 50),
        ]);
    }

    /** Lightweight feed for the nav dropdown (JSON). */
    public function feed(Request $request): JsonResponse
    {
        return response()->json([
            'notifications' => $this->recent($request, 12),
            'unread' => $request->user()->unreadNotificationsCount(),
        ]);
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);
        $notification->forceFill(['read_at' => now()])->save();

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back();
    }

    public function destroy(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);
        $notification->delete();

        return back();
    }

    private function recent(Request $request, int $limit): \Illuminate\Support\Collection
    {
        return $request->user()->notifications()
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn (Notification $n) => [
                'id' => $n->id,
                'icon' => $n->icon,
                'title' => $n->title,
                'body' => $n->body,
                'url' => $n->url,
                'when' => $n->created_at->diffForHumans(),
                'read' => $n->read_at !== null,
            ]);
    }
}
