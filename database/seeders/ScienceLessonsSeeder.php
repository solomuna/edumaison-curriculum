<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceLessonsSeeder extends Seeder
{
    public function run(): void
    {
        $this->scienceC4();
        $this->scienceC5();
        $this->command->info('✅ Science lessons créées pour C4 et C5');
    }

    private function theme(int $subjectId, string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)).'-'.$subjectId;
        return DB::table('integrated_themes')->insertGetId([
            'subject_id' => $subjectId,
            'name'       => $name,
            'slug'       => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function unit(int $themeId, string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)).'-'.$themeId;
        return DB::table('units')->insertGetId([
            'integrated_theme_id' => $themeId,
            'name'                => $name,
            'slug'                => $slug,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }

    private function lesson(int $unitId, string $title, string $type = 'mixed'): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title)).'-'.$unitId;
        return DB::table('lessons')->insertGetId([
            'unit_id'    => $unitId,
            'name'      => $title,
            'slug'       => $slug,
            'type'       => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ── SCIENCE C4 (subject_id = 33) ─────────────────────────────────────
    private function scienceC4(): void
    {
        $sid = 33;

        // Theme 1: Health Education
        $t1 = $this->theme($sid, 'Health Education');
        $u1 = $this->unit($t1, 'The Human Body');
        $this->lesson($u1, 'Bones and the Skeleton', 'science');
        $this->lesson($u1, 'The Senses', 'science');
        $u2 = $this->unit($t1, 'Diseases and Prevention');
        $this->lesson($u2, 'Water-borne and Insect-borne Diseases', 'science');
        $this->lesson($u2, 'Hygiene and First Aid', 'science');
        $u3 = $this->unit($t1, 'Food and Nutrition');
        $this->lesson($u3, 'Balanced Diet', 'science');

        // Theme 2: Environmental Science
        $t2 = $this->theme($sid, 'Environmental Science');
        $u4 = $this->unit($t2, 'Living Things');
        $this->lesson($u4, 'Animals and their Habitats', 'science');
        $this->lesson($u4, 'Plants and Seeds', 'science');
        $u5 = $this->unit($t2, 'Matter and Water');
        $this->lesson($u5, 'Properties of Matter', 'science');
        $this->lesson($u5, 'Water and Pollution', 'science');
        $u6 = $this->unit($t2, 'Soils');
        $this->lesson($u6, 'Types of Soil', 'science');

        // Theme 3: Technology
        $t3 = $this->theme($sid, 'Technology and Engineering');
        $u7 = $this->unit($t3, 'Machines and Energy');
        $this->lesson($u7, 'Simple Machines', 'science');
        $this->lesson($u7, 'Energy Sources', 'science');
        $u8 = $this->unit($t3, 'Electricity and Safety');
        $this->lesson($u8, 'Electric Circuits', 'science');

        $this->command->info('   Science C4 : 3 themes, 8 units, 13 lessons');
    }

    // ── SCIENCE C5 (subject_id = 39) ─────────────────────────────────────
    private function scienceC5(): void
    {
        $sid = 39;

        // Theme 1: Health Education
        $t1 = $this->theme($sid, 'Health Education');
        $u1 = $this->unit($t1, 'Body Systems');
        $this->lesson($u1, 'Digestive and Circulatory Systems', 'science');
        $this->lesson($u1, 'Respiratory and Excretory Systems', 'science');
        $u2 = $this->unit($t1, 'Reproductive Health');
        $this->lesson($u2, 'Puberty and Adolescence', 'science');
        $this->lesson($u2, 'STIs and HIV Prevention', 'science');
        $u3 = $this->unit($t1, 'Diseases and Public Health');
        $this->lesson($u3, 'Non-communicable Diseases', 'science');
        $this->lesson($u3, 'Vaccines and Immunity', 'science');

        // Theme 2: Environmental Science
        $t2 = $this->theme($sid, 'Environmental Science');
        $u4 = $this->unit($t2, 'Animals and Plants');
        $this->lesson($u4, 'Animal Habitats and Reproduction', 'science');
        $this->lesson($u4, 'Plant Life Cycles', 'science');
        $u5 = $this->unit($t2, 'Water Cycle and Pollution');
        $this->lesson($u5, 'The Water Cycle', 'science');
        $this->lesson($u5, 'Waste Management', 'science');
        $u6 = $this->unit($t2, 'Soils and Environment');
        $this->lesson($u6, 'Soil Types and Enrichment', 'science');

        // Theme 3: Technology
        $t3 = $this->theme($sid, 'Technology and Engineering');
        $u7 = $this->unit($t3, 'Forces and Machines');
        $this->lesson($u7, 'Push, Pull, Friction and Tension', 'science');
        $this->lesson($u7, 'Civil Engineering Basics', 'science');
        $u8 = $this->unit($t3, 'Energy and Electricity');
        $this->lesson($u8, 'Energy Forms and Sources', 'science');
        $this->lesson($u8, 'Conductors and Insulators', 'science');

        $this->command->info('   Science C5 : 3 themes, 8 units, 15 lessons');
    }
}
