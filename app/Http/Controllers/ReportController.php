<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(Report::TYPES))],
            'id' => ['required'],
            'reason' => ['required', Rule::in(array_keys(Report::REASONS))],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $modelClass = Report::TYPES[$data['type']];
        $reportable = $modelClass::query()->findOrFail($data['id']);

        $user = $request->user();

        // One open report per user per item — silently succeed if already reported.
        $already = Report::where('user_id', $user->id)
            ->where('reportable_type', $reportable->getMorphClass())
            ->where('reportable_id', $reportable->getKey())
            ->where('status', 'open')
            ->exists();

        if (! $already) {
            $report = new Report([
                'user_id' => $user->id,
                'reason' => $data['reason'],
                'details' => $data['details'] ?? null,
                'status' => 'open',
            ]);
            $report->reportable()->associate($reportable);
            $report->save();
        }

        return back()->with('success', 'Bedankt voor je melding. Een beheerder bekijkt het zo snel mogelijk. 💛');
    }
}
