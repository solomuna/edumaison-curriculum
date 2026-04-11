<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedistributeUnitsC1C3Seeder extends Seeder
{
    public function run(): void
    {
        // Maths C1, C2, C3
        $this->mathsLevel(13, 'C1', [
            'Unit 1 — Numbers & Counting'   => ['number','count','digit','place','order','biggest','smallest','missing','more','less','equal'],
            'Unit 2 — Addition & Subtraction'=> ['add','subtract','plus','minus','sum','difference','total','change','left','word problem'],
            'Unit 3 — Shapes & Geometry'    => ['shape','geometry','circle','square','triangle','rectangle','line','straight','curved'],
            'Unit 4 — Measurement'          => ['measure','length','mass','weight','volume','metre','gram','litre','ruler','scale','longer','heavier'],
            'Unit 5 — Time & Money'         => ['time','clock','minute','hour','day','week','month','money','coin','price','cost'],
            'Unit 6 — Sets & Patterns'      => ['set','pattern','group','sort','match','tally','fraction','half','quarter'],
        ]);

        $this->mathsLevel(19, 'C2', [
            'Unit 1 — Numbers & Operations' => ['number','count','digit','place','order','missing','even','odd','prime','addition','subtract','multiply','divide','times','table','bodmas'],
            'Unit 2 — Fractions'            => ['fraction','half','quarter','third','equivalent','simplif'],
            'Unit 3 — Geometry'             => ['shape','geometry','circle','square','triangle','rectangle','pentagon','rhombus','line','angle','parallel','vertical','horizontal','oblique'],
            'Unit 4 — Measurement'          => ['measure','length','mass','weight','volume','metre','gram','litre','convert','cm','mm','kg','ml'],
            'Unit 5 — Time & Money'         => ['time','clock','minute','hour','day','week','month','year','money','coin','price','cost','change','total'],
            'Unit 6 — Sets & Statistics'    => ['set','venn','tally','frequency','graph','mean','mode','median','pictogram','bar'],
        ]);

        $this->mathsLevel(25, 'C3', [
            'Unit 1 — Numbers & Operations' => ['number','count','digit','place','order','bodmas','prime','factor','multiple','lcm','hcf','nearest','round'],
            'Unit 2 — Fractions & Decimals' => ['fraction','decimal','percentage','ratio','equivalent','simplif','lcm','hcf'],
            'Unit 3 — Geometry'             => ['shape','geometry','circle','square','triangle','rectangle','pentagon','rhombus','line','angle','parallel','perpendicular','polygon','perimeter','area'],
            'Unit 4 — Measurement'          => ['measure','length','mass','weight','volume','metre','gram','litre','convert','cm','mm','kg','ml','dl','cl','km','time','clock','speed'],
            'Unit 5 — Money & Statistics'   => ['money','profit','loss','cost','price','change','total','set','venn','tally','frequency','graph','mean','mode','median','statistic'],
        ]);

        // English C1, C2, C3
        $this->englishLevel(12, 'C1', [
            'Unit 1 — Phonics & Reading'  => ['phonics','letter','sound','read','word','sight','rhyme','syllable','vowel','consonant','alphabet'],
            'Unit 2 — Grammar'            => ['grammar','noun','verb','adjective','pronoun','article','plural','tense','sentence','subject'],
            'Unit 3 — Vocabulary'         => ['vocabulary','synonym','antonym','opposite','meaning','word family'],
            'Unit 4 — Comprehension'      => ['comprehension','passage','story','text','question','answer'],
        ]);

        $this->englishLevel(18, 'C2', [
            'Unit 1 — Phonics & Reading'  => ['phonics','letter','sound','read','word','sight','rhyme','syllable','vowel','consonant','cvc'],
            'Unit 2 — Grammar'            => ['grammar','noun','verb','adjective','pronoun','article','plural','tense','sentence','subject','preposition'],
            'Unit 3 — Vocabulary'         => ['vocabulary','synonym','antonym','opposite','meaning','word family','compound','prefix'],
            'Unit 4 — Comprehension'      => ['comprehension','passage','story','text','question','answer','main idea'],
        ]);

        $this->englishLevel(24, 'C3', [
            'Unit 1 — Grammar'        => ['grammar','noun','verb','adjective','pronoun','article','plural','tense','sentence','adverb','preposition','conjunction'],
            'Unit 2 — Vocabulary'     => ['vocabulary','synonym','antonym','opposite','meaning','word','compound','prefix','suffix'],
            'Unit 3 — Comprehension'  => ['comprehension','passage','story','text','question','answer','main idea','inference'],
            'Unit 4 — Writing Skills' => ['writing','sentence','paragraph','fill','composition','punctuation'],
        ]);

        $this->command->info('✅ Units redistributed for Maths and English C1-C3');
    }

    private function mathsLevel(int $sid, string $label, array $units): void
    {
        $tid = DB::table('integrated_themes')->where('subject_id', $sid)->value('id');
        if (!$tid) { $this->command->warn("No theme for Maths $label"); return; }

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   Maths $label — $unitName: $count ex");
        }
    }

    private function englishLevel(int $sid, string $label, array $units): void
    {
        $tid = DB::table('integrated_themes')->where('subject_id', $sid)->value('id');
        if (!$tid) { $this->command->warn("No theme for English $label"); return; }

        foreach ($units as $unitName => $keywords) {
            $unitId = $this->mkUnit($tid, $unitName);
            $lessonId = $this->mkLesson($unitId, $unitName);
            $count = $this->moveExercises($keywords, $lessonId, $sid);
            $this->command->info("   English $label — $unitName: $count ex");
        }
    }

    private function mkUnit(int $themeId, string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)) . '-' . $themeId;
        $existing = DB::table('units')->where('slug', $slug)->value('id');
        if ($existing) return $existing;
        return DB::table('units')->insertGetId([
            'integrated_theme_id' => $themeId, 'name' => $name, 'slug' => $slug,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function mkLesson(int $unitId, string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)) . '-u' . $unitId;
        $existing = DB::table('lessons')->where('slug', $slug)->value('id');
        if ($existing) return $existing;
        return DB::table('lessons')->insertGetId([
            'unit_id' => $unitId, 'name' => $name, 'slug' => $slug,
            'type' => 'mixed', 'created_at' => now(), 'updated_at' => now(),
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
            ->pluck('exercises.id')->toArray();

        if (count($exercises) > 0) {
            DB::table('exercises')->whereIn('id', $exercises)->update(['lesson_id' => $lessonId]);
        }
        return count($exercises);
    }
}
