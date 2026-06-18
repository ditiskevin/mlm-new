<?php

namespace App\Http\Controllers;

use App\Models\PregnancyWeek;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    public function index(): Response
    {
        $weeks = PregnancyWeek::orderBy('position')->get()->map(fn (PregnancyWeek $w) => [
            'position' => $w->position,
            'label' => $w->label,
            'trimester' => $w->trimester,
            'triLabel' => $w->trimester === 1 ? '1e trimester' : ($w->trimester === 2 ? '2e trimester' : '3e trimester'),
            'fruit' => $w->fruit,
            'emoji' => $w->emoji,
            'size' => $w->size,
            'baby' => $w->baby,
            'mom' => $w->mom,
            'tip' => $w->tip,
            'milestone' => $w->milestone,
            'echo' => $w->echo,
            'courseTip' => $w->course_tip,
            'warning' => $w->warning,
            'nursery' => $w->nursery,
        ]);

        return Inertia::render('Calendar', [
            'weeks' => $weeks,
        ]);
    }
}
