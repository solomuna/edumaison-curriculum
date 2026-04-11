<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulletinsSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id') ?? 2;

        $this->seedIrma($schoolYearId);
        $this->seedMark($schoolYearId);
        $this->seedRuth($schoolYearId);

        $this->command->info('✅ Bulletins seedés pour Irma, Mark et Ruth');
    }

    private function ins(int $childId, int $schoolYearId, int $subjectId, float $avg, float $total, float $max, string $appreciation, string $comment): void
    {
        DB::table('school_results')->insert([
            'child_id'       => $childId,
            'school_year_id' => $schoolYearId,
            'subject_id'     => $subjectId,
            'total_score'    => $total,
            'max_score'      => $max,
            'average_score'  => $avg,
            'appreciation'   => $appreciation,
            'teacher_comment'=> $comment,
            'source_type'    => 'manual',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    // ── IRMA — Class 1 (level_id=5) — moy 15.54 rang 27e ────────────────
    private function seedIrma(int $sy): void
    {
        $id = 1;
        // subjects C1: 12=English, 13=Maths, 14=French, 15=Science, 16=Reading, 17=Handwriting
        $data = [
            [12, 16.5, 'Très bien', 'Excellent travail en lecture et expression orale.'],
            [13, 14.0, 'Bien',      'Bonne compréhension des nombres. Continuer les exercices de calcul.'],
            [14, 15.5, 'Très bien', 'Très bonne participation en français.'],
            [15, 16.0, 'Très bien', 'Curieuse et attentive en sciences.'],
            [16, 17.0, 'Excellent', 'Lecture fluide et expressive.'],
            [17, 14.0, 'Bien',      'Écriture soignée. Travailler la régularité des lettres.'],
        ];
        foreach ($data as [$sid, $avg, $app, $comment]) {
            $this->ins($id, $sy, $sid, $avg, $avg * 2, 20.0, $app, $comment);
        }
        $this->command->info("   Irma (C1) : moy ".round(array_sum(array_column($data,1))/count($data),2)." — rang 27e");
    }

    // ── MARK — Class 4 (level_id=8) — moy 7.32 rang 34e ────────────────
    private function seedMark(int $sy): void
    {
        $id = 2;
        // subjects C4: 30=English, 31=Maths, 32=French, 33=Science, 34=ICT, 35=Citizenship
        $data = [
            [30, 9.5,  'Passable',    'Des efforts à fournir en grammaire et composition.'],
            [31, 5.5,  'Insuffisant', 'Grandes difficultés en fractions et géométrie. Plan de remédiation activé.'],
            [32, 8.0,  'Passable',    'Compréhension correcte à l\'oral, difficultés à l\'écrit.'],
            [33, 4.5,  'Insuffisant', 'Notions de base non acquises. Révision urgente recommandée.'],
            [34, 9.0,  'Passable',    'Intérêt pour l\'ICT. Travailler les exercices pratiques.'],
            [35, 9.0,  'Passable',    'Participation correcte en classe.'],
        ];
        foreach ($data as [$sid, $avg, $app, $comment]) {
            $this->ins($id, $sy, $sid, $avg, $avg * 2, 20.0, $app, $comment);
        }
        $this->command->info("   Mark (C4) : moy ".round(array_sum(array_column($data,1))/count($data),2)." — rang 34e ⚠️");
    }

    // ── RUTH — Class 5 (level_id=9) — moy 14.91 rang 71e ───────────────
    private function seedRuth(int $sy): void
    {
        $id = 3;
        // subjects C5: 36=English, 37=Maths, 38=French, 39=Science, 40=ICT, 41=Citizenship
        $data = [
            [36, 16.0, 'Très bien', 'Excellente en composition et expression écrite.'],
            [37, 15.5, 'Très bien', 'Bonne maîtrise des fractions et de la géométrie.'],
            [38, 14.0, 'Bien',      'Bonne progression en expression écrite française.'],
            [39, 15.0, 'Très bien', 'Très bonne compréhension des sciences naturelles.'],
            [40, 13.5, 'Bien',      'Bonne maîtrise des outils informatiques.'],
            [41, 15.5, 'Très bien', 'Très impliquée dans les activités civiques.'],
        ];
        foreach ($data as [$sid, $avg, $app, $comment]) {
            $this->ins($id, $sy, $sid, $avg, $avg * 2, 20.0, $app, $comment);
        }
        $this->command->info("   Ruth (C5) : moy ".round(array_sum(array_column($data,1))/count($data),2)." — rang 71e");
    }
}
