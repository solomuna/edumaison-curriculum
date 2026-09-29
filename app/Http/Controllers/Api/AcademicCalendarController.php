<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\JsonResponse;

class AcademicCalendarController extends Controller
{
    public function index(): JsonResponse
    {
        $years = SchoolYear::query()
            ->with(['terms' => fn ($query) => $query->orderBy('order'), 'terms.sequences' => fn ($query) => $query->orderBy('order')])
            ->orderByDesc('label')
            ->limit(2)
            ->get()
            ->map(fn (SchoolYear $year) => [
                'label' => $year->label,
                'is_current' => $year->is_current,
                'status' => $year->official_status,
                'start_date' => $year->start_date?->toDateString(),
                'end_date' => $year->end_date?->toDateString(),
                'source' => [
                    'authority' => $year->source_authority,
                    'url' => $year->source_url,
                    'reference' => $year->source_reference,
                    'published_at' => $year->published_at?->toDateString(),
                ],
                'terms' => $year->terms->map(fn ($term) => [
                    'name' => $term->name,
                    'order' => $term->order,
                    'start_date' => $term->start_date?->toDateString(),
                    'end_date' => $term->end_date?->toDateString(),
                    'sequences' => $term->sequences->map(fn ($sequence) => [
                        'name' => $sequence->name,
                        'order' => $sequence->order,
                        'start_date' => $sequence->start_date?->toDateString(),
                        'end_date' => $sequence->end_date?->toDateString(),
                    ])->values(),
                ])->values(),
            ]);

        return response()->json(['years' => $years]);
    }
}
