<?php

namespace App\Support;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** Année scolaire affichée : celle demandée (?school_year_id=…), sinon l'année en cours. */
class SchoolYears
{
    public static function current(): ?SchoolYear
    {
        return SchoolYear::query()->where('is_current', true)->orderByDesc('start_date')->orderByDesc('id')->first();
    }

    public static function resolve(Request $request): ?SchoolYear
    {
        $id = (int) $request->query('school_year_id', 0);
        return ($id > 0 ? SchoolYear::query()->find($id) : null) ?? self::current();
    }

    /**
     * Années consultables par une famille : l'année en cours et celles où ses
     * enfants ont des exercices. De la plus récente à la plus ancienne.
     *
     * @return Collection<int, array{id: int, label: string, is_current: bool}>
     */
    public static function forHousehold(int $householdId): Collection
    {
        $withAttempts = DB::table('exercise_attempts')
            ->join('children', 'children.id', '=', 'exercise_attempts.child_id')
            ->where('children.household_id', $householdId)
            ->distinct()
            ->pluck('exercise_attempts.school_year_id');

        return SchoolYear::query()
            ->where(fn ($q) => $q->where('is_current', true)->orWhereIn('id', $withAttempts))
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get()
            ->unique('label')
            ->map(fn (SchoolYear $year) => ['id' => $year->id, 'label' => (string) $year->label, 'is_current' => (bool) $year->is_current])
            ->values();
    }
}
