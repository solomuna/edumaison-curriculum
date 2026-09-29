<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const CATEGORIES = [
        'reading',
        'handwriting',
        'writing',
        'listening',
        'speaking',
        'vocabulary',
        'mathematics',
        'science',
        'ict',
        'revision',
        'quiz',
        'oral_drill',
    ];

    public function up(): void
    {
        $this->replaceCategoryConstraint(self::CATEGORIES);

        DB::table('exercises')
            ->where('category', 'handwriting')
            ->whereRaw("content->>'type' = ?", ['written_response'])
            ->update(['category' => 'writing', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('exercises')
            ->where('category', 'writing')
            ->update(['category' => 'handwriting', 'updated_at' => now()]);

        $this->replaceCategoryConstraint(array_values(array_filter(
            self::CATEGORIES,
            fn (string $category): bool => $category !== 'writing',
        )));
    }

    private function replaceCategoryConstraint(array $categories): void
    {
        DB::statement('ALTER TABLE exercises DROP CONSTRAINT IF EXISTS exercises_category_check');
        $quoted = collect($categories)
            ->map(fn (string $category): string => DB::getPdo()->quote($category))
            ->implode(', ');
        DB::statement("ALTER TABLE exercises ADD CONSTRAINT exercises_category_check CHECK (category IN ({$quoted}))");
    }
};
