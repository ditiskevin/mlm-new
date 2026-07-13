<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class BlockController extends Controller
{
    /**
     * Block / unblock another member.
     */
    public function toggle(Request $request, User $user): RedirectResponse
    {
        $me = $request->user();

        if ($user->id === $me->id) {
            return back()->with('error', 'Je kunt jezelf niet blokkeren.');
        }

        $existing = DB::table('user_blocks')
            ->where('blocker_id', $me->id)
            ->where('blocked_id', $user->id)
            ->exists();

        if ($existing) {
            DB::table('user_blocks')
                ->where('blocker_id', $me->id)
                ->where('blocked_id', $user->id)
                ->delete();

            return back()->with('success', 'Blokkade opgeheven.');
        }

        DB::table('user_blocks')->insert([
            'blocker_id' => $me->id,
            'blocked_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Lid geblokkeerd.');
    }

    /**
     * List the members the current user has blocked.
     */
    public function index(Request $request): Response
    {
        $me = $request->user();

        $blocked = DB::table('user_blocks')
            ->join('users', 'users.id', '=', 'user_blocks.blocked_id')
            ->where('user_blocks.blocker_id', $me->id)
            ->orderByDesc('user_blocks.created_at')
            ->get([
                'users.id',
                'users.name',
                'users.avatar_color',
                'users.avatar_path',
            ])
            ->map(function ($u) {
                $avatarUrl = null;
                if ($u->avatar_path) {
                    try {
                        $avatarUrl = \Illuminate\Support\Facades\Storage::disk('r2')->url($u->avatar_path);
                    } catch (\Throwable $e) {
                        $avatarUrl = null;
                    }
                }

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'initial' => mb_substr($u->name ?? '?', 0, 1),
                    'avatar_color' => $u->avatar_color ?: '#F7A8B5',
                    'avatar_url' => $avatarUrl,
                ];
            })
            ->all();

        return Inertia::render('Members/Blocked', [
            'blocked' => $blocked,
        ]);
    }
}
