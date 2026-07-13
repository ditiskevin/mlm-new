<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    /**
     * Show the welcome / onboarding wizard shown once, right after registration.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        // Already been through the wizard? Straight to the dashboard.
        if ($user->onboarded_at !== null) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Onboarding/Wizard', [
            'parentTypes' => User::parentTypeMap(),
            'parentingRoles' => User::PARENTING_ROLES,
        ]);
    }

    /**
     * Persist the wizard answers and mark the member as onboarded.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_expecting' => ['required', 'boolean'],
            // Kept nullable so "Sla over" / no due date still works.
            'due_date' => ['nullable', 'date'],
            'children_count' => ['nullable', 'integer', 'min:0', 'max:12'],
            'parent_type' => ['nullable', Rule::in(array_keys(User::parentTypeMap()))],
            'parenting_role' => ['nullable', Rule::in(array_keys(User::PARENTING_ROLES))],
        ]);

        $user = $request->user();

        // is_expecting / due_date / children_count are guarded (not in #[Fillable]),
        // so set them explicitly on the model before saving.
        $user->is_expecting = (bool) $validated['is_expecting'];
        $user->due_date = $validated['is_expecting'] ? ($validated['due_date'] ?? null) : null;
        $user->children_count = $validated['children_count'] ?? null;

        if (! empty($validated['parent_type'])) {
            $user->parent_type = $validated['parent_type'];
        }
        if (! empty($validated['parenting_role'])) {
            $user->parenting_role = $validated['parenting_role'];
        }

        $user->onboarded_at = now();
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Fijn dat je er bent! Je profiel is klaar. 💛');
    }
}
