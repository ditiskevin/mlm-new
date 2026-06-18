<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ChecklistController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $checkedIds = $user
            ? $user->checkedItems()->pluck('checklist_items.id')->flip()
            : collect();

        $build = function (string $type) use ($checkedIds) {
            return ChecklistItem::where('type', $type)
                ->orderBy('position')
                ->get()
                ->groupBy('category')
                ->map(fn ($items, $category) => [
                    'name' => $category,
                    'items' => $items->map(fn (ChecklistItem $it) => [
                        'id' => $it->id,
                        'label' => $it->label,
                        'done' => $checkedIds->has($it->id),
                    ])->values(),
                ])->values();
        };

        return Inertia::render('Checklists', [
            'lists' => [
                'uitzet' => $build('uitzet'),
                'vluchttas' => $build('vluchttas'),
            ],
        ]);
    }

    public function toggle(ChecklistItem $item): RedirectResponse
    {
        Auth::user()->checkedItems()->toggle($item->id);

        return back();
    }
}
