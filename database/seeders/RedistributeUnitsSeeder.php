<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedistributeUnitsSeeder extends Seeder
{
    public function run(): void
    {
        $this->mathsC4();
        $this->mathsC5();
        $this->mathsC6();
        $this->englishC4();
        $this->englishC5();
        $this->englishC6();
        $this->command->info('✅ Units redistributed for Maths and English C4-C6');
    }

    private function mkUnit(int $themeId, string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)) . '-' . $themeId;
        $existing = DB::table('units')->where('slug', $slug)->value('id');
        if ($existing) return $existing;
        return DB::table('units')->insertGetId([
            'integrated_theme_id' => $themeId,
            'name' => $name,
            'slug' => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function getThemeId(int $subjectId): int
    {
        return DB::table('integrated_themes')->where('subject_id', $subjectId)->value('id');
    }

    private function mkLesson(int $unitId, string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)) . '-u' . $unitId;
        $existing = DB::table('lessons')->where('slug', $slug)->value('id');
        if ($existing) return $existing;
        return DB::table('lessons')->insertGetId([
            'unit_id' => $unitId,
            'name' => $name,
            'slug' => $slug,
            'type' => 'mixed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function moveExercises(array $keywords, int $lessonId, int $subjectId): int
    {
        $exercises = DB::table('exercises')
            ->join('lessons as l', 'exercises.lesson_id', '=', 'l.id')
            ->join('units as u', 'l.unit_id', '=', 'u.id')
            ->join('integrated_themes as t', 'u.integrated_theme_id', '=', 't.id')
            ->where('t.subject_id', $subjectId)
            ->where(function($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('exercises.title', 'ilike', "%$kw%")
                      ->orWhere('exercises.category', 'ilike', "%$kw%");
                }
            })
            ->pluck('exercises.id')
            ->toArray();

        if (count($exercises) > 0) {
            DB::table('exercises')->whereIn('id', $exercises)->update(['lesson_id' => $lessonId]);
        }
        return count($exercises);
    }

    // ── MATHS C4 (subject 31) ─────────────────────────────────────────────
    private function mathsC4(): void
    {
        $sid = 31;
        $tid = $this->getThemeId($sid);
        if (!$tid) return;

        $units = [
            'Unit 1 — Numbers & Operations' => ['large number','calendar','count','number','place value','bodmas','prime','digit'],
            'Unit 2 — Fractions & Decimals' => ['fraction','decimal','lcm','hcf','ratio'],
            'Unit 3 — Geometry'             => ['shape','geometry','angle','perimeter','area','parallel','square','rectangle','circle','triangle','line','polygon'],
            'Unit 4 — Measurement'          => ['measure','length','mass','weight','volume','capacity','metre','gram','litre','convert','km','kg','cm','mm','time','clock','speed','distance'],
            'Unit 5 — Money & Data'         => ['profit','loss','money','cost','price','venn','set','statistic','graph','tally','mean','mode','median'],
        ];

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   C4 {$unitName}: $count exercises");
        }
    }

    // ── MATHS C5 (subject 37) ─────────────────────────────────────────────
    private function mathsC5(): void
    {
        $sid = 37;
        $tid = $this->getThemeId($sid);
        if (!$tid) return;

        $units = [
            'Unit 1 — Numbers & Operations' => ['number','bodmas','prime','factor','multiple','digit','nearest','round','significant','standard form','base','binary'],
            'Unit 2 — Fractions & Decimals' => ['fraction','decimal','percentage','ratio','proportion','lcm','hcf'],
            'Unit 3 — Geometry'             => ['shape','geometry','angle','perimeter','area','parallel','square','rectangle','circle','triangle','line','polygon','parallelogram','trapez','venn','number line'],
            'Unit 4 — Measurement & Time'   => ['measure','length','mass','weight','volume','capacity','metre','gram','litre','convert','km','kg','cm','time','clock','speed','distance','duration'],
            'Unit 5 — Money & Statistics'   => ['profit','loss','money','cost','price','interest','discount','tax','statistic','graph','mean','mode','median','probability','set'],
        ];

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   C5 {$unitName}: $count exercises");
        }
    }

    // ── MATHS C6 (subject 43) ─────────────────────────────────────────────
    private function mathsC6(): void
    {
        $sid = 43;
        $tid = $this->getThemeId($sid);
        if (!$tid) return;

        $units = [
            'Unit 1 — Numbers & Operations' => ['number','bodmas','prime','factor','multiple','digit','nearest','round','significant','standard form','base','binary','octal','recurring'],
            'Unit 2 — Fractions & Decimals' => ['fraction','decimal','percentage','ratio','proportion','lcm','hcf','compound'],
            'Unit 3 — Geometry'             => ['shape','geometry','angle','perimeter','area','parallel','square','rectangle','circle','triangle','line','polygon','parallelogram','trapez','circumference','radius','diameter','pie chart','reflex'],
            'Unit 4 — Measurement & Time'   => ['measure','length','mass','weight','volume','capacity','metre','gram','litre','convert','km','kg','cm','time','clock','speed','distance','duration','zone'],
            'Unit 5 — Money & Statistics'   => ['profit','loss','money','cost','price','interest','discount','tax','statistic','graph','mean','mode','median','probability','set','scatter','frequency','sector'],
        ];

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   C6 {$unitName}: $count exercises");
        }
    }

    // ── ENGLISH C4 (subject 30) ───────────────────────────────────────────
    private function englishC4(): void
    {
        $sid = 30;
        $tid = $this->getThemeId($sid);
        if (!$tid) return;

        $units = [
            'Unit 1 — Grammar'        => ['grammar','tense','verb','noun','adjective','pronoun','adverb','conjunction','preposition','article','comparative','superlative','passive','direct','indirect','clause','sentence'],
            'Unit 2 — Vocabulary'     => ['vocabulary','synonym','antonym','homophone','compound','idiom','prefix','suffix','word','meaning','opposite'],
            'Unit 3 — Comprehension'  => ['comprehension','reading','passage','story','text','main idea','inference','author'],
            'Unit 4 — Writing Skills' => ['writing','essay','letter','paragraph','punctuation','capital','composition','fill'],
        ];

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   English C4 {$unitName}: $count exercises");
        }
    }

    // ── ENGLISH C5 (subject 36) ───────────────────────────────────────────
    private function englishC5(): void
    {
        $sid = 36;
        $tid = $this->getThemeId($sid);
        if (!$tid) return;

        $units = [
            'Unit 1 — Grammar'        => ['grammar','tense','verb','noun','adjective','pronoun','adverb','conjunction','preposition','modal','passive','relative','conditional','reported','clause','gerund','subjunctive'],
            'Unit 2 — Vocabulary'     => ['vocabulary','synonym','antonym','prefix','suffix','word','meaning','opposite','benevolent','eloquent'],
            'Unit 3 — Comprehension'  => ['comprehension','reading','passage','story','text','main idea','inference','water cycle','climate'],
            'Unit 4 — Writing Skills' => ['writing','essay','letter','paragraph','punctuation','fill','composition'],
        ];

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   English C5 {$unitName}: $count exercises");
        }
    }

    // ── ENGLISH C6 (subject 42) ───────────────────────────────────────────
    private function englishC6(): void
    {
        $sid = 42;
        $tid = $this->getThemeId($sid);
        if (!$tid) return;

        $units = [
            'Unit 1 — Grammar'          => ['grammar','tense','verb','noun','adjective','pronoun','adverb','past perfect','future perfect','subjunctive','inversion','gerund','reflexive','punctuation'],
            'Unit 2 — Vocabulary'       => ['vocabulary','synonym','antonym','compound','word','meaning','eloquent','abstract'],
            'Unit 3 — Comprehension'    => ['comprehension','reading','passage','story','text','main idea','inference','tone','author'],
            'Unit 4 — Literature'       => ['literature','poetry','stanza','rhyme','narrative','simile','metaphor','personification','alliteration','onomatopoeia','figure'],
            'Unit 5 — Writing Skills'   => ['writing','essay','letter','paragraph','fill','composition','expository','argumentative','formal'],
        ];

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   English C6 {$unitName}: $count exercises");
        }
    }
}
